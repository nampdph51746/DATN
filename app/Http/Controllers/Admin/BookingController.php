<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointHistory;

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
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'success'])],
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        // Cộng điểm khi chuyển từ pending sang confirmed hoặc success
        if ($oldStatus === 'pending' && in_array($booking->status, ['confirmed', 'success'])) {
            $user = $booking->user;

            $pointsToAdd = max(1, floor($booking->final_amount / 10000));

            if ($pointsToAdd > 0 && !PointHistory::where('booking_id', $booking->id)->exists()) {
                $point = Point::firstOrCreate(['user_id' => $user->id]);
                $point->total_points = ($point->total_points ?? 0) + $pointsToAdd;
                $point->save();

                PointHistory::create([
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'points_change' => $pointsToAdd,
                    'reason_type' => 'booking_success',
                    'description' => 'Cộng điểm cho đơn hàng #' . $booking->id,
                ]);
            }
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái thành công.');
    }
}
