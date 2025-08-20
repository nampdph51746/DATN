<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessTimeoutBookingAttempts;
use App\Services\BookingAttemptService;

class ProcessBookingTimeouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:process-timeouts {--sync : Process synchronously without queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process timeout booking attempts and check for user bans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing timeout booking attempts...');
        
        if ($this->option('sync')) {
            // Xử lý đồng bộ
            $service = new BookingAttemptService();
            $result = $service->processTimeoutAttempts();
            
            $this->info("Processed {$result['processed']} timeout attempts");
            $this->info("Created {$result['bans_created']} new bans");
            
            if ($result['bans_created'] > 0) {
                $this->warn("⚠️  {$result['bans_created']} user(s) have been banned for 7 days");
            }
        } else {
            // Xử lý qua queue (cách cũ)
            ProcessTimeoutBookingAttempts::dispatch();
            $this->info('Dispatched ProcessTimeoutBookingAttempts job');
            $this->comment('Run "php artisan queue:work --once" to process the job');
        }
        
        return 0;
    }
}
