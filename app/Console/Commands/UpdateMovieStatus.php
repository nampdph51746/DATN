<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Enums\MovieStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMovieStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động cập nhật trạng thái phim dựa trên ngày kết thúc';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang cập nhật trạng thái phim...');
        
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
            $this->info("Đã cập nhật trạng thái cho {$totalUpdated} phim:");
            if ($upcomingToShowing > 0) {
                $this->info("- {$upcomingToShowing} phim từ 'Sắp chiếu' -> 'Đang chiếu'");
            }
            if ($showingToEnded > 0) {
                $this->info("- {$showingToEnded} phim từ 'Đang chiếu' -> 'Đã kết thúc'");
            }
            Log::info("Auto-updated {$totalUpdated} movies status");
        } else {
            $this->info('Không có phim nào cần cập nhật trạng thái');
        }
        
        // Hiển thị thống kê
        $showingCount = Movie::where('status', MovieStatus::Showing)->count();
        $upcomingCount = Movie::where('status', MovieStatus::Upcoming)->count();
        $endedCount = Movie::where('status', MovieStatus::Ended)->count();
        
        $this->info("Thống kê hiện tại:");
        $this->info("- Phim đang chiếu: {$showingCount}");
        $this->info("- Phim sắp chiếu: {$upcomingCount}");
        $this->info("- Phim đã kết thúc: {$endedCount}");
        
        return Command::SUCCESS;
    }
}
