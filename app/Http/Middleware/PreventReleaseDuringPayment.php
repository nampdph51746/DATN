<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PreventReleaseDuringPayment
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('PreventReleaseDuringPayment middleware triggered', [
            'is_checkout' => session('is_checkout'),
            'is_processing_payment' => session('is_processing_payment'),
            'user_booking_in_progress' => session('user_booking_in_progress'),
            'url' => $request->url()
        ]);
        
        // Chỉ chặn release khi thực sự đang thanh toán, không chặn khi đang chọn ghế
        if (session('is_processing_payment')) {
            Log::info('PreventReleaseDuringPayment: Blocking seat release during payment processing');
            return response()->json([
                'error' => 'Đang xử lý thanh toán, không thể thay đổi ghế',
                'message' => 'Không thể thực hiện thao tác này khi đang xử lý thanh toán'
            ], 403);
        }
        
        // Chặn release khi đã vào bước checkout và form đã được submit
        if (session('is_checkout') && session('checkout_form_submitted')) {
            Log::info('PreventReleaseDuringPayment: Blocking seat release after checkout form submitted');
            return response()->json([
                'error' => 'Đang thanh toán, không thể thay đổi ghế',
                'message' => 'Không thể thực hiện thao tác này khi đang thanh toán'
            ], 403);
        }
        
        Log::info('PreventReleaseDuringPayment: Allowing request to proceed');
        return $next($request);
    }
}