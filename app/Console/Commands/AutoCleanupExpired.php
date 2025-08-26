<?php

namespace App\Console\Commands;

use App\Services\BookingAttemptService;
use Illuminate\Console\Command;

class AutoCleanupExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:auto-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto cleanup expired booking attempts (run by scheduler)';

    /**
     * Execute the console command.
     */
    public function handle(BookingAttemptService $bookingAttemptService): int
    {
        $this->info('Auto cleanup expired booking attempts...');
        
        // Chạy trực tiếp cleanup mà không cần queue
        $result = $bookingAttemptService->processTimeoutAttempts();
        
        $this->info("Processed {$result['processed']} timeout attempts");
        $this->info("Created {$result['bans_created']} new bans");
        
        // Cleanup thêm lần nữa để chắc chắn
        $cleanupResult = $bookingAttemptService->cleanupExpiredAttempts(0);
        $this->info("Additional cleanup: {$cleanupResult['total_deleted']} deleted");
        
        return Command::SUCCESS;
    }
}
