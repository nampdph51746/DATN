<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BookingAttemptService;
use Illuminate\Support\Facades\Log;

class AutoCleanupBookingAttempts
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
        // Cleanup expired booking attempts mỗi 10 requests (tăng tần suất)
        if (rand(1, 10) === 1) {
            try {
                $result = $this->bookingAttemptService->cleanupExpiredAttempts(0); // Xóa ngay khi expired
                if ($result['total_deleted'] > 0) {
                    Log::info("Auto cleanup: Deleted {$result['total_deleted']} expired booking attempts");
                }
            } catch (\Exception $e) {
                Log::warning("Auto cleanup failed: " . $e->getMessage());
            }
        }

        return $next($request);
    }
}
