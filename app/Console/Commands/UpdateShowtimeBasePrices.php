<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Showtime;
use App\Services\ShowtimePricingService;

class UpdateShowtimeBasePrices extends Command
{
    protected $signature = 'showtime:update-prices {--dry-run : Show what would be updated without making changes}';
    protected $description = 'Update base_price for all showtimes based on their room type';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $pricingService = new ShowtimePricingService();
        
        $this->info('Starting showtime base price update...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }
        
        $showtimes = Showtime::with('room.roomType')->get();
        $updatedCount = 0;
        $totalCount = $showtimes->count();
        
        $this->info("Found {$totalCount} showtimes to process");
        
        foreach ($showtimes as $showtime) {
            $oldPrice = $showtime->base_price;
            $newPrice = $pricingService->calculateBasePriceForRoom($showtime->room);
            
            if ($oldPrice != $newPrice) {
                $roomTypeName = $showtime->room->roomType->name ?? 'Unknown';
                $this->line("Showtime ID {$showtime->id}: {$oldPrice} -> {$newPrice} ({$roomTypeName})");
                
                if (!$isDryRun) {
                    $showtime->base_price = $newPrice;
                    $showtime->save();
                }
                
                $updatedCount++;
            }
        }
        
        if ($isDryRun) {
            $this->info("DRY RUN: Would update {$updatedCount} out of {$totalCount} showtimes");
        } else {
            $this->info("Successfully updated {$updatedCount} out of {$totalCount} showtimes");
        }
        
        return 0;
    }
}
