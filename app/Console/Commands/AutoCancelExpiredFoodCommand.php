<?php

namespace App\Console\Commands;

use App\Jobs\AutoCancelExpiredFood;
use Illuminate\Console\Command;

class AutoCancelExpiredFoodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'food:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy đồ ăn đã hết hạn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to cancel expired food items...');
        
        // Dispatch job để cancel đồ ăn hết hạn
        AutoCancelExpiredFood::dispatch();
        
        $this->info('Job dispatched successfully. Check logs for details.');
        
        return 0;
    }
}
