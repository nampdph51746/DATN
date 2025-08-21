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
            'url' => $request->url()
        ]);
        
        // Kiểm tra nếu đang ở bước checkout hoặc đang xử lý thanh toán
        if (session('is_checkout') || session('is_processing_payment')) {
            Log::info('PreventReleaseDuringPayment: Blocking seat release during payment');
            return response()->json([
                'error' => 'Đang thanh toán, không thể release ghế',
                'message' => 'Không thể thực hiện thao tác này khi đang thanh toán'
            ], 403);
        }
        
        Log::info('PreventReleaseDuringPayment: Allowing request to proceed');
        return $next($request);
    }
}