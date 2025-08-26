<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BookingAttemptService;

class AutoCleanupExpiredAttempts
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
        // Cleanup expired booking attempts với xác suất cao
        if (rand(1, 3) === 1) { // 33% chance mỗi request
            try {
                $this->bookingAttemptService->cleanupExpiredAttempts(0);
            } catch (\Exception $e) {
                // Bỏ qua lỗi để không ảnh hưởng request
            }
        }

        return $next($request);
    }
}
