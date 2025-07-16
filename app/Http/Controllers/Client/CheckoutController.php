<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
public function previewBooking(Request $request)
{
    $bookingData = [
        'user_id' => Auth::id(),
        'booking_code' => 'BK' . now()->timestamp,
        'total_amount_before_discount' => $request->input('total_amount_before_discount'),
        'discount_amount' => $request->input('discount_amount', 0),
        'final_amount' => $request->input('final_amount'),
        'promotion_id' => $request->input('promotion_id'),
        'payment_method_id' => 1, // VNPAY
        'status' => 'pending',
        'notes' => null,
        'movie_title' => $request->input('movie_title'),
        'cinema_name' => $request->input('cinema_name'),
        'room_name' => $request->input('room_name'),
        'showtime' => $request->input('showtime'),
        'snack_items' => $request->input('snack_items'),
        'items' => is_string($request->input('items'))
            ? (json_decode($request->input('items', '[]'), true) ?? [])
            : ($request->input('items') ?? []),
    ];
    
    // dd($bookingData); 
    session(['booking_preview' => $bookingData]);

    return view('client.checkout', compact('bookingData'));
}

}
