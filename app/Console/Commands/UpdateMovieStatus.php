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
        
        // Cập nhật phim đã kết thúc
        $updatedCount = Movie::where('end_date', '<', now())
                             ->where('status', '!=', MovieStatus::Ended)
                             ->update(['status' => MovieStatus::Ended]);
        
        if ($updatedCount > 0) {
            $this->info("Đã cập nhật {$updatedCount} phim sang trạng thái 'Đã kết thúc'");
            Log::info("Auto-updated {$updatedCount} movies to 'ended' status");
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
