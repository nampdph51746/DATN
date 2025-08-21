<?php

namespace App\Console\Commands;

use App\Jobs\ReleaseExpiredSeats;
use Illuminate\Console\Command;

class ReleaseExpiredSeatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release expired seat reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching ReleaseExpiredSeats job...');
        
        ReleaseExpiredSeats::dispatch();
        
        $this->info('ReleaseExpiredSeats job dispatched successfully!');
        
        return 0;
    }
}
