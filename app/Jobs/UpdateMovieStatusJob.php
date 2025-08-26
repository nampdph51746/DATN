<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Enums\MovieStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateMovieStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = now()->toDateString();
        
        // Cập nhật phim sắp chiếu -> đang chiếu
        $upcomingToShowing = Movie::where('status', MovieStatus::Upcoming)
                                  ->where('release_date', '<=', $today)
                                  ->update(['status' => MovieStatus::Showing]);
                                  
        // Cập nhật phim đang chiếu -> đã kết thúc (nếu có ngày kết thúc)
        $showingToEnded = Movie::where('status', MovieStatus::Showing)
                               ->whereNotNull('end_date')
                               ->where('end_date', '<', $today)
                               ->update(['status' => MovieStatus::Ended]);
        
        $totalUpdated = $upcomingToShowing + $showingToEnded;
        
        if ($totalUpdated > 0) {
            Log::info("Auto-updated {$totalUpdated} movies status", [
                'upcoming_to_showing' => $upcomingToShowing,
                'showing_to_ended' => $showingToEnded,
                'date' => $today
            ]);
        }
    }
}
