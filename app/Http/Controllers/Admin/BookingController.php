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
use App\Enums\PointReasonType;


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
            'status' => ['required', Rule::in([
                BookingStatus::Pending->value,
                BookingStatus::Confirmed->value,
                BookingStatus::Cancelled->value
            ])],
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status->value;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->route('admin.bookings.index')->with('info', 'Trạng thái không thay đổi.');
        }

        $booking->status = BookingStatus::from($newStatus);
        $booking->save();

        $user = $booking->user;
        if (!$user) {
            return redirect()->route('admin.bookings.index')->with('error', 'Không tìm thấy người dùng.');
        }

        $existingHistory = PointHistory::where('booking_id', $booking->id)->latest()->first();

        // ✅ CỘNG điểm khi từ Pending → Confirmed
        if ($oldStatus === BookingStatus::Pending->value && $newStatus === BookingStatus::Confirmed->value) {
            $pointsToAdd = max(1, floor($booking->final_amount / 10000));

            if ($pointsToAdd > 0 && !$existingHistory) {
                $point = Point::firstOrCreate(
                    ['user_id' => $user->id],
                    ['points_expiry_date' => now()->addYear()]
                );
                $point->total_points += $pointsToAdd;
                $point->save();

                PointHistory::create([
                    'user_id'       => $user->id,
                    'booking_id'    => $booking->id,
                    'points_change' => $pointsToAdd,
                    'reason_type'   => PointReasonType::Earned->value,
                    'description'   => 'Cộng điểm cho đơn hàng #' . $booking->id,
                ]);
            }
        }

        // ✅ TRỪ điểm khi từ Confirmed → Pending (nếu đã từng cộng)
        if ($oldStatus === BookingStatus::Confirmed->value && $newStatus === BookingStatus::Pending->value && $existingHistory && $existingHistory->points_change > 0) {
            $point = Point::firstOrCreate(
                ['user_id' => $user->id],
                ['points_expiry_date' => now()->addYear()]
            );
            $point->total_points = max(0, $point->total_points - $existingHistory->points_change);
            $point->save();

            PointHistory::create([
                'user_id'       => $user->id,
                'booking_id'    => $booking->id,
                'points_change' => -$existingHistory->points_change,
                'reason_type'   => PointReasonType::Spent->value,
                'description'   => 'Trừ điểm do thay đổi trạng thái đơn hàng #' . $booking->id,
            ]);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
