<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller; // ⚠️ cần import Controller gốc
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class BookingHistoryController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $statusFilter = $request->query('status'); // ví dụ: confirmed, pending, cancelled

    $bookingsQuery = $user->bookings()->with(['tickets.showtime.movie', 'tickets.seat.room.cinema', 'items.productVariant.product']);

    if ($statusFilter && in_array($statusFilter, ['pending', 'confirmed', 'cancelled', 'refunded', 'expired'])) {
        $bookingsQuery->where('status', $statusFilter);
    }

    $bookings = $bookingsQuery->orderByDesc('created_at')->get();

    return view('client.booking-history', compact('bookings', 'statusFilter'));
}


   public function show($id)
{
    $booking = Auth::user()->bookings()
        ->with([
            'items.productVariant.product' // để phân loại ghế / đồ ăn
        ])
        ->findOrFail($id);

    return view('client.booking-show', compact('booking'));
}


}
