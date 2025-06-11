<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointHistory;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query();

        // Tìm kiếm theo ID, mã booking, hoặc user_id
        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search)
                        ->orWhere('user_id', $search);
                }
                $q->orWhere('booking_code', 'like', "%$search%");
            });
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
            'promotion'
            // Thêm eager load cho sản phẩm
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }



    public function editStatus(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in([BookingStatus::Pending->value, BookingStatus::Confirmed->value, \App\Enums\BookingStatus::Cancelled->value])],
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status->value; // Lấy giá trị chuỗi của enum
        $booking->status = BookingStatus::from($request->status); // Chuyển chuỗi thành enum
        $booking->save();

        // \Log::info("Booking ID: {$booking->id}, Old Status: {$oldStatus}, New Status: {$booking->status->value}, Final Amount: {$booking->final_amount}");

        if ($oldStatus === BookingStatus::Pending->value && $booking->status->value === BookingStatus::Confirmed->value) {
            $user = $booking->user;
            // if (!$user) {
            //     \Log::error("User not found for booking ID: {$booking->id}, User ID: {$booking->user_id}");
            //     return redirect()->route('admin.bookings.index')->with('error', 'Không tìm thấy người dùng.');
            // }

            $pointsToAdd = max(1, floor($booking->final_amount / 10000));
            // \Log::info("Points to add: {$pointsToAdd}, Booking ID: {$booking->id}, User ID: {$user->id}");

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
                        'description' => 'Cộng điểm cho đơn hàng #' . $booking->id,
                        'created_at' => now(),
                    ]);

                    // \Log::info("Points added for user ID: {$user->id}, Booking ID: {$booking->id}, Points: {$pointsToAdd}");
                } catch (\Exception $e) {
                    // \Log::error("Failed to add points for booking ID: {$booking->id}, Error: {$e->getMessage()}");
                    return redirect()->route('admin.bookings.index')->with('error', 'Lỗi khi cộng điểm thưởng: ' . $e->getMessage());
                }
            } else {
                // \Log::warning("Points not added. Points: {$pointsToAdd}, Existing history: " . (PointHistory::where('booking_id', $booking->id)->exists() ? 'Yes' : 'No'));
            }
        }
return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái thành công.');
    }
}
