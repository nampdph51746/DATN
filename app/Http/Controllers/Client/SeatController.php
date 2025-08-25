<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\SeatType;
use App\Models\Showtime;
use App\Enums\SeatStatus;
use Illuminate\Http\Request;
use App\Events\SeatStatusUpdated;
use App\Events\SeatReleased;
use App\Models\ShowtimeSeatState;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\BookingAttemptService;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{
    protected $bookingAttemptService;

    public function __construct(BookingAttemptService $bookingAttemptService)
    {
        $this->bookingAttemptService = $bookingAttemptService;
    }
    // API bỏ giữ ghế khi người dùng thoát hoặc reload trang
    public function releaseSeat(Request $request, $showtimeId)
    {
        // Chỉ chặn release khi thực sự đang xử lý thanh toán hoặc form đã submit
        if (session('is_processing_payment') || (session('is_checkout') && session('checkout_form_submitted'))) {
            Log::info('releaseSeat: Bỏ qua release do đang xử lý thanh toán');
            return response()->json(['message' => 'Không thực hiện release khi đang xử lý thanh toán']);
        }

        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {
            $seatIds = $request->input('seat_ids');

            // Nếu request đến từ bước thanh toán thì không thực hiện release
            if ($request->has('from_payment') && $request->input('from_payment') == 1) {
                Log::info('releaseSeat: Bỏ qua release do đang thanh toán');
                return response()->json(['message' => 'Không thực hiện release khi thanh toán']);
            }

            // KHÔNG cancel booking attempt khi release seat để chọn ghế khác
            // Chỉ cancel khi thực sự thoát trang (trong releaseAllSeatsOfSession)
            // Điều này cho phép logic createAttempt() tự động update attempt hiện tại
            if (Auth::check()) {
                $userId = Auth::id();
                Log::info("Released seats for user {$userId}, showtime {$showtimeId} - keeping booking attempt active for potential update");
            }

            foreach ($seatIds as $id) {
                $seatState = ShowtimeSeatState::where('showtime_id', $showtimeId)
                    ->where('seat_id', $id)
                    ->first();

                if ($seatState && $seatState->status === SeatStatus::Reserved) {
                    $seatState->status = SeatStatus::Available;
                    $seatState->locked_until = null;
                    $seatState->locked_by = null;
                    $seatState->save();
                    
                    // Broadcast event để thông báo ghế đã available
                    event(new SeatStatusUpdated($showtimeId, $id, SeatStatus::Available, null, null));
                    event(new SeatReleased($showtimeId, $id, $request->session()->getId()));
                    Log::info("Released seat ID $id for showtime ID: $showtimeId");
                }
            }

            return response()->json(['message' => 'Ghế đã được bỏ giữ']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi bỏ giữ ghế.'], 500);
        }
    }

    public function showSeatMap($showtimeId)
    {
        Log::info('Starting showSeatMap for showtime ID: ' . $showtimeId);
        $showtime = Showtime::with([
            'room.seats.seatType',
            'room.seats.showtimeSeatStates' => function ($q) use ($showtimeId) {
                $q->where('showtime_id', $showtimeId);
            }
        ])->findOrFail($showtimeId);
        Log::info('Room for showtime ID ' . $showtimeId . ': ' . $showtime->room->name);

        $room = $showtime->room;

        $bookedSeats = Ticket::where('showtime_id', $showtimeId)
            ->whereHas('booking', fn ($query) => $query->where('status', 'confirmed'))
            ->pluck('seat_id')
            ->toArray();

        Log::info('Booked seats for showtime ID ' . $showtimeId . ': ' . json_encode($bookedSeats));

        $seats = $room->seats->map(function ($seat) use ($bookedSeats, $showtime) {
            $label = $seat->row_char . '-' . str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
            $price = $seat->seatType->price_modifier ?? $showtime->base_price; // Sử dụng price_modifier, fallback về base_price
            
            // Kiểm tra trạng thái từ showtimes_seat_states, fallback về seats.status
            $seatState = $seat->showtimeSeatStates->first();
            $status = 'available';
            
            if (in_array($seat->id, $bookedSeats)) {
                $status = 'reserved';
            } elseif ($seatState && $seatState->status === SeatStatus::Reserved) {
                // Ghế đang được giữ tạm thời (locked)
                $status = 'locked';
            } elseif ($seatState && $seatState->status === SeatStatus::Maintenance) {
                $status = 'maintenance';
            } elseif ($seatState && $seatState->status === SeatStatus::Booked) {
                $status = 'reserved';
            } elseif ($seat->status === SeatStatus::Maintenance) {
                // Fallback: nếu không có seatState nhưng ghế gốc là maintenance
                $status = 'maintenance';
            }
            
            $seatInfo = [
                'seat_id'     => $seat->id,
                'label'       => $label,
                'row_char'    => $seat->row_char,
                'seat_number' => $seat->seat_number,
                'status'      => $status,
                'seat_type'   => $seat->seatType->name,
                'color_code'  => $seat->seatType->color_code,
                'price'       => $price,
                'locked_by'   => $seatState ? $seatState->locked_by : null,
            ];
            Log::info('Seat processed for showSeatMap: ' . json_encode($seatInfo));
            return $seatInfo;
        });

        $roomMeta = [
            'id'        => $room->id,
            'name'      => $room->name,
            'rows'      => $room->seats->pluck('row_char')->unique()->count(),
            'cols'      => $room->seats->pluck('seat_number')->max(),
            'row_chars' => $room->seats->pluck('row_char')->unique()->values()->toArray(),
        ];

        Log::info('Room meta for showtime ID ' . $showtimeId . ': ' . json_encode($roomMeta));

        $seatTypes = $room->seats->pluck('seatType')->unique('id');
        Log::info('Seat types loaded: ' . json_encode($seatTypes->map(function ($type) {
            return [
                'name' => $type->name,
                'price_modifier' => $type->price_modifier,
            ];
        })->toArray()));

        Log::info('Rendering seat_selection view for showtime ID: ' . $showtimeId);
        return view('client.seat_selection', [
            'showtime'       => $showtime,
            'room'           => $roomMeta,
            'seats'          => $seats,
            'seatTypes'      => $seatTypes,
            'bookedSeats'    => $bookedSeats,
            'pusher_key'     => config('broadcasting.connections.pusher.key'),
            'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
        ]);
    }

    public function reserveSeat(Request $request, $showtimeId)
    {
        Log::info('Starting reserveSeat for showtime ID: ' . $showtimeId . ', request data: ' . json_encode($request->all()));
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {
            $seatIds = $request->input('seat_ids');
            Log::info('Seat IDs to reserve: ' . json_encode($seatIds));

            // Kiểm tra giới hạn số ghế tối đa
            $maxSeats = config('booking.max_seats_per_booking', 8);
            if (count($seatIds) > $maxSeats) {
                return response()->json([
                    'error' => 'Vượt quá giới hạn đặt ghế',
                    'message' => "Bạn chỉ có thể đặt tối đa {$maxSeats} ghế trong 1 lần đặt vé",
                ], 400);
            }

            // Kiểm tra user đã login chưa
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để đặt ghế'], 401);
            }

            $userId = Auth::id();

            // Kiểm tra user có bị ban không
            if ($this->bookingAttemptService->isUserBanned($userId)) {
                $banInfo = $this->bookingAttemptService->getUserBanInfo($userId);
                return response()->json([
                    'error' => 'Tài khoản của bạn đã bị tạm khóa đặt vé',
                    'message' => 'Bạn đã đặt ghế nhiều lần liên tiếp mà không thanh toán. Tài khoản sẽ được mở khóa vào ' . $banInfo->banned_until->format('d/m/Y H:i'),
                    'banned_until' => $banInfo->banned_until->toISOString(),
                ], 403);
            }

            // Kiểm tra số lần thất bại gần đây để cảnh báo trước khi ban
            $recentFailedAttempts = \App\Models\BookingAttempt::where('user_id', $userId)
                ->whereIn('status', [\App\Enums\BookingAttemptStatus::Timeout, \App\Enums\BookingAttemptStatus::Cancelled])
                ->where('reserved_at', '>=', now()->subDay())
                ->count();
                
            if ($recentFailedAttempts >= 2) {
                Log::warning("User {$userId} has {$recentFailedAttempts} failed attempts - close to ban limit");
                // Không block ngay, nhưng log để theo dõi
            }

            $states = ShowtimeSeatState::where('showtime_id', $showtimeId)
                ->whereIn('seat_id', $seatIds)
                ->get();
            Log::info('Current seat states: ' . json_encode($states->toArray()));

            $sessionId = $request->session()->getId();
            Log::info('Session ID for reserving seats: ' . $sessionId);

            foreach ($seatIds as $id) {
                $seat = Seat::find($id);
                $seatState = $states->firstWhere('seat_id', $id);
                
                // Kiểm tra ghế maintenance
                if (($seat && $seat->status === SeatStatus::Maintenance) || ($seatState && $seatState->status === SeatStatus::Maintenance)) {
                    Log::warning("Seat ID $id is under maintenance");
                    return response()->json([
                        'error' => "Ghế {$seat->row_char}{$seat->seat_number} đang bảo trì"
                    ], 400);
                }
                
                // Nếu đã reserved thì bỏ qua, không giữ nữa
                if ($seatState && $seatState->status === SeatStatus::Reserved) {
                    if ($seatState->locked_by !== $sessionId) {
                        Log::warning("Seat ID $id is locked by another session");
                        return response()->json([
                            'error' => "Ghế {$seatState->seat->row_char}{$seatState->seat->seat_number} không khả dụng"
                        ], 400);
                    }
                }

                // Không giữ ghế 10 phút nữa, chỉ giữ khi đang ở trang
                $seatState = ShowtimeSeatState::updateOrCreate(
                    ['showtime_id' => $showtimeId, 'seat_id' => $id],
                    [
                        'status' => SeatStatus::Reserved,
                        'locked_until' => null,
                        'booking_id' => null,
                        'locked_by' => $sessionId,
                    ]
                );

                Log::info("Seat ID $id reserved for showtime ID: $showtimeId");
                event(new SeatStatusUpdated($showtimeId, $id, SeatStatus::Reserved, null, $sessionId));
            }

            // Tạo booking attempt để tracking
            $attempt = $this->bookingAttemptService->createAttempt($userId, $showtimeId, $seatIds);
            Log::info("Created booking attempt {$attempt->id} for user {$userId}");

            $selectedSeatInfos = ShowtimeSeatState::with(['seat.seatType'])
                ->where('showtime_id', $showtimeId)
                ->whereIn('seat_id', $seatIds)
                ->get()
                ->map(function ($state) use ($showtimeId) {
                    $showtime = Showtime::find($showtimeId);
                    $ticketPrice = $showtime->base_price * ($state->seat->seatType->price_modifier ?? 1.0);
                    return [
                        'seat_id' => $state->seat_id,
                        'row_char' => $state->seat->row_char,
                        'seat_number' => $state->seat->seat_number,
                        'seat_type' => $state->seat->seatType->name ?? 'Regular',
                        'price_modifier' => $state->seat->seatType->price_modifier ?? 1.0,
                        'color_code' => $state->seat->seatType->color_code ?? '#28a745',
                        'price' => $ticketPrice,
                    ];
                })->toArray();
            Log::info('Selected seats info saved to session: ' . json_encode($selectedSeatInfos));
            session(['selected_seats_info' => $selectedSeatInfos]);

            Log::info('Seats reserved successfully for showtime ID: ' . $showtimeId);
            return response()->json([
                'message' => 'Đã giữ ghế tạm thời',
                'attempt_id' => $attempt->id,
                'expired_at' => $attempt->expired_at->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in reserveSeat for showtime ID: ' . $showtimeId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi khi giữ ghế. Vui lòng thử lại.'], 500);
        }
    }
    
    public function getSeatStatus($showtimeId)
    {
        Log::info('Starting getSeatStatus for showtime ID: ' . $showtimeId);

        $showtime = Showtime::with([
            'room.seats.seatType',
            'room.seats.showtimeSeatStates' => function ($q) use ($showtimeId) {
                $q->where('showtime_id', $showtimeId);
            }
        ])->findOrFail($showtimeId);

        $room = $showtime->room;

        if (!$room || $room->seats->isEmpty()) {
            Log::warning("Room or seats not found for showtime ID: $showtimeId");
            return response()->json(['error' => 'Phòng chiếu không có ghế.'], 404);
        }

        $seatData = $room->seats->map(function ($seat) use ($showtime) {
            $seatState = $seat->showtimeSeatStates->first();
            $price = $seat->seatType->price_modifier ?? $showtime->base_price;

            $status = 'available';
            $lockedBy = null;
            $lockedUntil = null;

            if ($seatState) {
                if ($seatState->status === SeatStatus::Reserved) {
                    // Ghế đang được reserve (locked) bởi session nào đó
                    $status = 'locked';
                    $lockedBy = $seatState->locked_by;
                    $lockedUntil = $seatState->locked_until ? $seatState->locked_until->toDateTimeString() : null;
                } elseif ($seatState->status === SeatStatus::Booked) {
                    $status = 'reserved';
                } elseif ($seatState->status === SeatStatus::Maintenance) {
                    $status = 'maintenance';
                }
            } elseif ($seat->status === SeatStatus::Maintenance) {
                // Fallback: nếu không có seatState nhưng ghế gốc là maintenance
                $status = 'maintenance';
            }

            return [
                'seat_id' => $seat->id,
                'status' => $status,
                'locked_by' => $lockedBy,
                'locked_until' => $lockedUntil,
            ];
        });

        return response()->json(['seats' => $seatData]);
    }

    // API để release tất cả ghế của session khi user disconnect
    public function releaseAllSeatsOfSession(Request $request, $showtimeId)
    {
        try {
            $sessionId = $request->session()->getId();
            
            // Tìm tất cả ghế đang được giữ bởi session hiện tại
            $seatStates = ShowtimeSeatState::where('showtime_id', $showtimeId)
                ->where('locked_by', $sessionId)
                ->where('status', SeatStatus::Reserved)
                ->get();

            if ($seatStates->isEmpty()) {
                Log::info("No seats to release for session {$sessionId} in showtime {$showtimeId}");
                return response()->json(['message' => 'Không có ghế nào cần trả lại']);
            }

            $releasedSeatIds = [];
            
            // Chỉ chặn release khi thực sự đang xử lý thanh toán hoặc form đã submit
            if (session('is_processing_payment') || (session('is_checkout') && session('checkout_form_submitted'))) {
                Log::info('releaseAllSeatsOfSession: Bỏ qua release do đang xử lý thanh toán');
                return response()->json(['message' => 'Không thực hiện release khi đang xử lý thanh toán']);
            }

            foreach ($seatStates as $seatState) {
                $seatState->status = SeatStatus::Available;
                $seatState->locked_until = null;
                $seatState->locked_by = null;
                $seatState->save();
                
                $releasedSeatIds[] = $seatState->seat_id;
                
                // Broadcast events
                event(new SeatStatusUpdated($showtimeId, $seatState->seat_id, SeatStatus::Available, null, null));
                event(new SeatReleased($showtimeId, $seatState->seat_id, $sessionId));
                
                Log::info("Released seat ID {$seatState->seat_id} for session {$sessionId} in showtime {$showtimeId}");
            }

            // Nếu user đã login, hủy booking attempt 
            // NHƯNG chỉ khi không có booking nào đang được tạo gần đây (trong 5 phút)
            if (Auth::check()) {
                $userId = Auth::id();
                
                // Kiểm tra xem có booking nào được tạo trong 5 phút gần đây không
                $recentBooking = \App\Models\Booking::where('user_id', $userId)
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->exists();
                
                // Chỉ cancel booking attempt nếu:
                // 1. Không đang trong quá trình thanh toán
                // 2. Không có booking gần đây (tránh cancel khi vừa thanh toán xong)
                if (!session('is_processing_payment') && 
                    !session('checkout_form_submitted') && 
                    !$recentBooking) {
                    $this->bookingAttemptService->cancelActiveAttempts($userId, $showtimeId);
                    Log::info("Cancelled active booking attempts for user {$userId}, showtime {$showtimeId}");
                } else {
                    Log::info("Skipped cancelling booking attempts for user {$userId} - recent booking or payment in progress");
                }
            }

            return response()->json([
                'message' => 'Đã trả lại tất cả ghế',
                'released_seats' => $releasedSeatIds
            ]);
        } catch (\Exception $e) {
            Log::error("Error releasing all seats for session in showtime {$showtimeId}: " . $e->getMessage());
            return response()->json(['error' => 'Lỗi khi trả lại ghế.'], 500);
        }
    }
}