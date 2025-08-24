<?php

namespace App\Console\Commands;

use App\Services\BookingAttemptService;
use Illuminate\Console\Command;

class CleanupBookingAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:cleanup-attempts {--days=0 : Xóa dữ liệu cũ hơn số ngày này, 0 = xóa ngay khi expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old booking attempts that have expired';

    /**
     * Execute the console command.
     */
    public function handle(BookingAttemptService $bookingAttemptService): int
    {
        $days = (int) $this->option('days');
        
        $this->info("Cleaning up booking attempts older than {$days} days...");
        
        $result = $bookingAttemptService->cleanupExpiredAttempts($days);
        
        $this->info("Cleanup completed:");
        $this->info("- Deleted timeout/cancelled attempts: {$result['deleted_timeouts']}");
        $this->info("- Deleted completed attempts: {$result['deleted_completed']}");
        $this->info("- Total deleted: {$result['total_deleted']}");
        
        return Command::SUCCESS;
    }
}
