<?php

namespace App\Jobs;

use App\Services\BookingAttemptService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTimeoutBookingAttempts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(BookingAttemptService $bookingAttemptService): void
    {
        Log::info('Processing timeout booking attempts...');
        
        $bookingAttemptService->processTimeoutAttempts();
        $bookingAttemptService->cleanupExpiredBans();
        
        // Cleanup các attempts cũ mỗi lần chạy (xóa ngay khi expired)
        $bookingAttemptService->cleanupExpiredAttempts(0); // 0 = xóa ngay khi expired
        
        Log::info('Completed processing timeout booking attempts');
    }
}
