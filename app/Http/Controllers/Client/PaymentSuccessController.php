<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
class PaymentSuccessController extends Controller
{
    public function show(Request $request)
    {
        // Lấy booking mới nhất của user (hoặc từ session nếu cần)
        $booking = Booking::with(['tickets.seat', 'tickets.showtime', 'tickets.showtime.movie', 'tickets.showtime.room'])
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        if (!$booking || $booking->tickets->isEmpty()) {
            return view('client.payment.success'); // fallback, không có vé
        }

        $ticket = $booking->tickets->first();
        $movie = $ticket->showtime->movie;
        $room = $ticket->showtime->room;
        $showtime = $ticket->showtime;
        $seat = $ticket->seat;
        $ticketRow = $seat->row_char ?? null;
        $ticketSeat = $seat->seat_number ?? null;
        $totalPrice = $booking->final_amount;
        $bookingCode = $booking->booking_code;

        return view('client.payment.success', compact('movie', 'room', 'showtime', 'ticketRow', 'ticketSeat', 'totalPrice', 'bookingCode'));
    }

    /**
     * Clear all payment-related session data and redirect to home
     */
   
}