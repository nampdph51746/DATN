<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\BookingAttemptService;
use Illuminate\Support\Facades\Auth;

class CheckBookingBan
{
    protected $bookingAttemptService;

    public function __construct(BookingAttemptService $bookingAttemptService)
    {
        $this->bookingAttemptService = $bookingAttemptService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $userId = Auth::id();
        
        // Cleanup expired bans trước khi check
        $this->bookingAttemptService->cleanupExpiredBans();
        
        // Cleanup expired booking attempts mỗi request (xóa ngay khi expired)
        // Chạy thường xuyên hơn để đảm bảo cleanup kịp thời
        if (rand(1, 2) === 1) { // 50% chance
            try {
                $this->bookingAttemptService->cleanupExpiredAttempts(0); // 0 = xóa ngay khi expired
            } catch (\Exception $e) {
                // Bỏ qua lỗi để không ảnh hưởng đến request chính
            }
        }
        
        if ($this->bookingAttemptService->isUserBanned($userId)) {
            $banInfo = $this->bookingAttemptService->getUserBanInfo($userId);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Tài khoản của bạn đã bị tạm khóa đặt vé',
                    'message' => 'Bạn đã đặt ghế nhiều lần liên tiếp mà không thanh toán. Tài khoản sẽ được mở khóa vào ' . $banInfo->banned_until->format('d/m/Y H:i'),
                    'banned_until' => $banInfo->banned_until->toISOString(),
                    'reason' => $banInfo->reason,
                ], 403);
            }

            return redirect()->route('client.home')->with('error', 
                'Tài khoản của bạn đã bị tạm khóa đặt vé do đặt ghế nhiều lần mà không thanh toán. ' .
                'Tài khoản sẽ được mở khóa vào ' . $banInfo->banned_until->format('d/m/Y H:i')
            );
        }

        return $next($request);
    }
}
