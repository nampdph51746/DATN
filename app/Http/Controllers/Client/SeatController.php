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
use App\Models\ShowtimeSeatState;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SeatController extends Controller
{
    // API bỏ giữ ghế khi người dùng thoát hoặc reload trang
    public function releaseSeat(Request $request, $showtimeId)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {
            $seatIds = $request->input('seat_ids');
            foreach ($seatIds as $id) {
                $seatState = ShowtimeSeatState::where('showtime_id', $showtimeId)
                    ->where('seat_id', $id)
                    ->first();
                if ($seatState && $seatState->status === SeatStatus::Reserved) {
                    $seatState->status = SeatStatus::Available;
                    $seatState->locked_until = null;
                    $seatState->locked_by = null;
                    $seatState->save();
                }
            }
            return response()->json(['message' => 'Ghế đã được bỏ giữ']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi bỏ giữ ghế.'], 500);
        }
    }
    public function getSeatsForShowtime($showtimeId)
    {
        \Log::info('Starting getSeatsForShowtime for showtime ID: ' . $showtimeId);


        $showtime = Showtime::with([
            'room.seats.seatType',
            'room.seats.showtimeSeatStates' => function ($q) use ($showtimeId) {
                $q->where('showtime_id', $showtimeId);
            }
        ])->findOrFail($showtimeId);

        \Log::info('Showtime loaded: ' . json_encode([

            'id' => $showtime->id,
            'room_id' => $showtime->room_id,
            'base_price' => $showtime->base_price,
        ]));

        $room = $showtime->room;

        if (!$room || $room->seats->isEmpty()) {
            \Log::warning("Room or seats not found for showtime ID: $showtimeId");
            return response()->json(['error' => 'Phòng chiếu không có ghế.'], 404);
        }

        \Log::info('Room loaded: ' . json_encode([
            'id' => $room->id,
            'name' => $room->name,
            'seat_count' => $room->seats->count(),
        ]));

        $rows = $room->seats->pluck('row_char')->unique()->count();
        $cols = $room->seats->pluck('seat_number')->max();

        \Log::info('Room dimensions: ' . json_encode([

            'rows' => $rows,
            'cols' => $cols,
        ]));

        $seatData = $room->seats->map(function ($seat) use ($showtime) {
            $seatState = $seat->showtimeSeatStates->first();
            $price = $seat->seatType->price_modifier ?? $showtime->base_price; // Sử dụng price_modifier, fallback về base_price
            $seatInfo = [
                'seat_id'       => $seat->id,
                'row_char'      => $seat->row_char,
                'seat_number'   => $seat->seat_number,
                'status'        => $seatState ? $seatState->status : $seat->status,
                'seat_type'     => $seat->seatType->name,
                'color_code'    => $seat->seatType->color_code,
                'price'         => $price,
                'locked_until'  => $seatState && $seatState->locked_until
                    ? $seatState->locked_until->toDateTimeString()
                    : null,
                'locked_by'     => $seatState ? $seatState->locked_by : null,
            ];
            \Log::info('Seat data processed: ' . json_encode($seatInfo));
            return $seatInfo;
        });

        $response = [
            'room' => [
                'id'    => $room->id,
            'name'  => $room->name,
                'rows'  => $rows,
                'cols'  => $cols,
            ],
            'seats' => $seatData,
            'showtime' => [
                'id'         => $showtime->id,
                'base_price' => $showtime->base_price,
            ],
        ];

        \Log::info('Returning seat data for showtime ID: ' . $showtimeId);
        return response()->json($response);
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

        \Log::info('Booked seats for showtime ID ' . $showtimeId . ': ' . json_encode($bookedSeats));

        $seats = $room->seats->map(function ($seat) use ($bookedSeats, $showtime) {
            $label = $seat->row_char . '-' . str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
            $price = $seat->seatType->price_modifier ?? $showtime->base_price; // Sử dụng price_modifier, fallback về base_price
            
            // Kiểm tra trạng thái từ showtimes_seat_states, fallback về seats.status
            $seatState = $seat->showtimeSeatStates->first();
            $status = 'available';
            
            if (in_array($seat->id, $bookedSeats)) {
                $status = 'reserved';
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
            ];
            \Log::info('Seat processed for showSeatMap: ' . json_encode($seatInfo));

            return $seatInfo;
        });

        $roomMeta = [
            'id'        => $room->id,
            'name'      => $room->name,
            'rows'      => $room->seats->pluck('row_char')->unique()->count(),
            'cols'      => $room->seats->pluck('seat_number')->max(),
            'row_chars' => $room->seats->pluck('row_char')->unique()->values()->toArray(),
        ];

        \Log::info('Room meta for showtime ID ' . $showtimeId . ': ' . json_encode($roomMeta));

        $seatTypes = $room->seats->pluck('seatType')->unique('id');
        \Log::info('Seat types loaded: ' . json_encode($seatTypes->map(function ($type) {

            return [
                'name' => $type->name,
                'price_modifier' => $type->price_modifier,
            ];
        })->toArray()));

        \Log::info('Rendering seat_selection view for showtime ID: ' . $showtimeId);
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
        \Log::info('Starting reserveSeat for showtime ID: ' . $showtimeId . ', request data: ' . json_encode($request->all()));

        $request->validate([
            'seat_ids'   => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        try {

            $seatIds = $request->input('seat_ids');
            Log::info('Seat IDs to reserve: ' . json_encode($seatIds));

            $states = ShowtimeSeatState::where('showtime_id', $showtimeId)
                ->whereIn('seat_id', $seatIds)
                ->get();

            \Log::info('Current seat states: ' . json_encode($states->toArray()));

            $sessionId = $request->session()->getId();
            \Log::info('Session ID for reserving seats: ' . $sessionId);

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
            return response()->json(['message' => 'Đã giữ ghế tạm thời']);
        } catch (\Exception $e) {
            Log::error('Error in reserveSeat for showtime ID: ' . $showtimeId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi khi giữ ghế. Vui lòng thử lại.'], 500);
        }
    }

    public function getSeatStatus($showtimeId)
    {
        \Log::info('Starting getSeatStatus for showtime ID: ' . $showtimeId);

        $showtime = Showtime::with([
            'room.seats.seatType',
            'room.seats.showtimeSeatStates' => function ($q) use ($showtimeId) {
                $q->where('showtime_id', $showtimeId);
            }
        ])->findOrFail($showtimeId);

        $room = $showtime->room;

        if (!$room || $room->seats->isEmpty()) {

            \Log::warning("Room or seats not found for showtime ID: $showtimeId");
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
                    if ($seatState->locked_until && now()->lt($seatState->locked_until)) {
                        $status = 'locked';
                        $lockedBy = $seatState->locked_by;
                        $lockedUntil = $seatState->locked_until->toDateTimeString();
                    } else {
                        // ❗ Nếu quá hạn thì reset lại ghế
                        $seatState->update([
                            'status' => SeatStatus::Available,
                            'locked_by' => null,
                            'locked_until' => null,
                            'booking_id' => null,
                        ]);
                        $status = 'available';
                    }
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
}