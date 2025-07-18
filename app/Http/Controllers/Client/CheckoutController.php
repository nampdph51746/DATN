<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Promotion;

class CheckoutController extends Controller
{
    public function previewBooking(Request $request)
    {
        $user = Auth::user();
        $promotionId = $request->input('promotion_id');
        
        // Debug: Log input data
        Log::info('CheckoutController - Input data:', [
            'all_input' => $request->all(),
            'promotion_id_raw' => $request->input('promotion_id'),
            'promotion_id_type' => gettype($request->input('promotion_id')),
            'user_id' => $user ? $user->id : null
        ]);
        
        // Xử lý promotion_id - đảm bảo nó là integer hoặc null
        if ($promotionId === '' || $promotionId === '0' || $promotionId === 0) {
            $promotionId = null;
        } else if ($promotionId) {
            $promotionId = (int) $promotionId;
        }
        
        Log::info('CheckoutController - Processed promotion_id:', [
            'original' => $request->input('promotion_id'),
            'processed' => $promotionId,
            'type' => gettype($promotionId)
        ]);
        
        // Kiểm tra mã giảm giá đã được sử dụng chưa
        if ($promotionId && $user) {
            $hasUsedPromotion = Booking::where('user_id', $user->id)
                ->where('promotion_id', $promotionId)
                ->where('status', 'confirmed')
                ->exists();
            
            if ($hasUsedPromotion) {
                return redirect()->back()->with('error', 'Mã giảm giá này đã được sử dụng trong đơn hàng trước đó.');
            }
            
            // Kiểm tra mã giảm giá có tồn tại và hợp lệ không
            $promotion = Promotion::find($promotionId);
            if (!$promotion || $promotion->status !== 'active' || $promotion->quantity <= 0) {
                return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.');
            }
        }
        
        $bookingData = [
            'user_id' => Auth::id(),
            'booking_code' => 'BK' . now()->timestamp,
            'total_amount_before_discount' => $request->input('total_amount_before_discount'),
            'discount_amount' => $request->input('discount_amount', 0),
            'final_amount' => $request->input('final_amount'),
            'promotion_id' => $promotionId,
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

        // Debug: Log booking data before saving to session
        Log::info('CheckoutController - Booking data to be saved in session:', [
            'promotion_id' => $promotionId,
            'user_id' => Auth::id(),
            'booking_code' => $bookingData['booking_code'],
            'final_amount' => $bookingData['final_amount']
        ]);

        // dd($bookingData); 
        session(['booking_preview' => $bookingData]);

        return view('client.checkout', compact('bookingData'));
    }
}