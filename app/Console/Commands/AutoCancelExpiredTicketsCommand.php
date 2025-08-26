<?php

namespace App\Console\Commands;

use App\Jobs\AutoCancelExpiredTickets;
use Illuminate\Console\Command;

class AutoCancelExpiredTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy vé đã hết hạn (không check-in sau khi suất chiếu kết thúc)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to cancel expired tickets...');
        
        // Dispatch job để cancel vé hết hạn
        AutoCancelExpiredTickets::dispatch();
        
        $this->info('Job dispatched successfully. Check logs for details.');
        
        return 0;
    }
}