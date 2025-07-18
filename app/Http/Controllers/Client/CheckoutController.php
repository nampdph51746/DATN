<?php

namespace App\Http\Controllers\Client;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        'promotion_id' => $request->input('promotion_id', null), // Lấy promotion_id từ request
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

    // Log để kiểm tra promotion_id
    Log::info('CheckOutController - Received booking data:', [
        'promotion_id' => $bookingData['promotion_id'],
        'request_data' => $request->all(),
    ]);

    // Kiểm tra tính hợp lệ của promotion_id
    if ($bookingData['promotion_id']) {
        $promotion = Promotion::where('id', $bookingData['promotion_id'])
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        if (!$promotion) {
            Log::warning('Invalid promotion_id in previewBooking:', [
                'promotion_id' => $bookingData['promotion_id'],
            ]);
            $bookingData['promotion_id'] = null; // Đặt lại thành null nếu không hợp lệ
            $bookingData['discount_amount'] = 0; // Đặt discount_amount về 0 nếu không có promotion
        }
    }

    session(['booking_preview' => $bookingData]);

    // Log để xác nhận dữ liệu lưu vào session
    Log::info('CheckOutController - Stored booking_preview in session:', [
        'booking_preview' => session('booking_preview'),
    ]);

    return view('client.checkout', compact('bookingData'));
}
}