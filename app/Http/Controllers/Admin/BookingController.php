<?php

namespace App\Http\Controllers\Admin;

use App\Models\Point;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Models\Notification;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use App\Services\QrcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ✅ Import Log facade

class BookingController extends Controller
{
    public function myBookings()
    {
        $user = Auth::user();

        $bookings = Booking::with([
            'tickets.showtime.movie',
            'tickets.showtime.room',
            'tickets.seat.seatType',
            'bookingItems.product'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

        return view('client.bookings.index', compact('bookings'));
    }

    public function myBookingsShow($id)
    {
        $user = Auth::user();

        $booking = Booking::with([
            'tickets.showtime.movie',
            'tickets.showtime.room',
            'tickets.seat.seatType',
            'bookingItems.productVariant.product'
        ])
        // ✅ Sửa lỗi dòng 30: Thay ->where('user_id', $user->id) thành null check
        ->where('user_id', $user ? $user->id : null)
        ->findOrFail($id);
        
        return view('client.bookings.show', compact('booking'));
    }
    
    public function index(Request $request)
    {
        $query = Booking::with(['tickets.showtime.movie', 'user']);

        // Tìm kiếm theo ID, mã booking, hoặc user_id
        if ($request->has('search') && $request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search)
                        ->orWhere('user_id', $search);
                }
                $q->orWhere('booking_code', 'like', "%$search%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%$search%")
                               ->orWhere('email', 'like', "%$search%")
                               ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Lọc theo payment_status
        if ($request->has('payment_status') && $request->payment_status !== null) {
            $query->where('payment_status', $request->payment_status);
        }

        // Lọc theo ngày
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // Lọc theo status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'tickets.showtime.movie',
            'tickets.showtime.room',
            'tickets.seat',
            'bookingItems.productVariant.product',
            'payments.paymentMethod',
            'promotion',
            'user'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Hiển thị form edit booking
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'tickets.showtime.movie', 'tickets.seat', 'payments']);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Cập nhật trạng thái booking
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', Rule::in([
                'pending', 'confirmed', 'cancelled', 'completed'
            ])],
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = is_object($booking->status) ? $booking->status->value : (string) $booking->status;
        
        // Cập nhật status
        if (enum_exists('App\Enums\BookingStatus')) {
            $booking->status = BookingStatus::from($request->status);
        } else {
            $booking->status = $request->status;
        }
        
        // Lưu ghi chú admin nếu có
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = $request->admin_notes;
        }
        
        $booking->save();

        // Tạo thông báo
        try {
            if (class_exists('App\Models\Notification')) {
                Notification::create([
                    'user_id' => Auth::id(),
                    'entity_type' => Booking::class,
                    'entity_id' => $booking->id,
                    'title' => 'Cập nhật trạng thái đơn đặt vé',
                    'message' => 'Đơn đặt vé #' . $booking->booking_code . ' đã được cập nhật từ "' . $oldStatus . '" sang "' . $request->status . '".',
                    'type' => 'booking',
                    'priority' => 'high',
                ]);
            }
        } catch (\Exception $e) {
            // ✅ Sửa \Log thành Log
            Log::warning('Could not create notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.bookings.index')
                        ->with('success', 'Cập nhật trạng thái đơn đặt vé thành công!');
    }

    /**
     * Cộng điểm thưởng cho booking được confirmed
     */
    private function addPointsForConfirmedBooking(Booking $booking)
    {
        $user = $booking->user;
        if (!$user) {
            return;
        }

        $pointsToAdd = max(1, floor($booking->final_amount / 10000));

        if ($pointsToAdd > 0 && !PointHistory::where('booking_id', $booking->id)->exists()) {
            try {
                $point = Point::firstOrCreate(
                    ['user_id' => $user->id],
                    ['points_expiry_date' => now()->addYear(), 'created_at' => now(), 'updated_at' => now()]
                );
                $point->total_points = ($point->total_points ?? 0) + $pointsToAdd;
                $point->save();

                PointHistory::create([
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'points_change' => $pointsToAdd,
                    'reason_type' => 'earned',
                    'description' => 'Cộng điểm cho đơn hàng #' . $booking->booking_code,
                    'created_at' => now(),
                ]);

            } catch (\Exception $e) {
                // ✅ Sửa \Log thành Log
                Log::error('Error adding points for booking: ' . $e->getMessage());
            }
        }
    }

    public function print($booking_code_or_id)
    {
        // Tìm booking theo code hoặc ID
        $booking = Booking::with([
            'tickets.showtime.movie',
            'tickets.showtime.room',
            'tickets.seat',
            'bookingItems.productVariant.product',
            'user',
        ])->where(function($query) use ($booking_code_or_id) {
            if (is_numeric($booking_code_or_id)) {
                $query->where('id', $booking_code_or_id)
                      ->orWhere('booking_code', $booking_code_or_id);
            } else {
                $query->where('booking_code', $booking_code_or_id);
            }
        })->firstOrFail();

        // Validate thời gian in vé cho tất cả các vé trong booking
        $this->validatePrintTimeForBooking($booking);

        $tickets = $booking->tickets;
        $foodDrinks = $booking->bookingItems;

        // Sinh QR code cho từng vé
        $qrService = app(QrcodeService::class);
        $ticketQRCodes = [];
        foreach ($tickets as $ticket) {
            $qrText = $ticket->ticket_code;
            $qrCodeRaw = $qrService->generateQrCode($qrText, 120);
            $ticketQRCodes[$ticket->id] = $qrCodeRaw ? 'data:image/png;base64,' . $qrCodeRaw : null;
        }

        // QR code cho food/drink chung
        $foodDrinksQRCode = null;
        if ($foodDrinks && $foodDrinks->count() > 0) {
            $qrText = json_encode($foodDrinks->toArray());
            $qrCodeRaw = $qrService->generateQrCode($qrText, 120);
            $foodDrinksQRCode = $qrCodeRaw ? 'data:image/png;base64,' . $qrCodeRaw : null;
        }

        return view('admin.bookings.print', [
            'booking' => $booking,
            'tickets' => $tickets,
            'foodDrinks' => $foodDrinks,
            'ticketQRCodes' => $ticketQRCodes,
            'foodDrinksQRCode' => $foodDrinksQRCode,
        ]);
    }

    /**
     * Validate thời gian in vé cho booking
     */
    private function validatePrintTimeForBooking($booking)
    {
        $currentTime = now();
        
        foreach ($booking->tickets as $ticket) {
            $showtime = $ticket->showtime;
            $showtimeStart = $showtime->start_time;
            
            // Có thể bỏ comment các validation này nếu cần
            // if ($currentTime >= $showtimeStart) {
            //     abort(403, 'Không thể in vé sau khi suất chiếu đã bắt đầu. Suất chiếu: ' . $showtimeStart->format('d/m/Y H:i'));
            // }
            
            // $oneHourBeforeShowtime = $showtimeStart->copy()->subHour();
            // if ($currentTime > $oneHourBeforeShowtime) {
            //     abort(403, 'Vé chỉ có thể in trước suất chiếu ít nhất 1 tiếng. Suất chiếu: ' . $showtimeStart->format('d/m/Y H:i'));
            // }
        }
    }
}