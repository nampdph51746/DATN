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

}
