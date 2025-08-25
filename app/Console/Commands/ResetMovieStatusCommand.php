<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Enums\MovieStatus;
use Illuminate\Console\Command;

class ResetMovieStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:reset-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset tất cả trạng thái phim về đúng logic theo thời gian hiện tại';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 ĐANG RESET TRẠNG THÁI TẤT CẢ PHIM...');
        
        $today = now()->toDateString();
        $this->info("📅 Ngày hiện tại: {$today}");
        
        $movies = Movie::all();
        $updated = 0;
        
        foreach ($movies as $movie) {
            $currentStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
            $correctStatus = $this->getCorrectStatus($movie->release_date, $movie->end_date, $today);
            
            if ($currentStatus !== $correctStatus) {
                $this->info("🔄 {$movie->name}: {$currentStatus} -> {$correctStatus}");
                $movie->update(['status' => $correctStatus]);
                $updated++;
            }
        }
        
        if ($updated > 0) {
            $this->info("✅ Đã reset {$updated} phim.");
        } else {
            $this->info("✅ Tất cả phim đều có trạng thái đúng.");
        }
        
        // Hiển thị thống kê cuối
        $showing = Movie::where('status', MovieStatus::Showing)->count();
        $upcoming = Movie::where('status', MovieStatus::Upcoming)->count();
        $ended = Movie::where('status', MovieStatus::Ended)->count();
        
        $this->table(
            ['Trạng thái', 'Số lượng'],
            [
                ['🎥 Đang chiếu', $showing],
                ['⏳ Sắp chiếu', $upcoming],
                ['✅ Đã kết thúc', $ended]
            ]
        );
        
        return Command::SUCCESS;
    }
    
    private function getCorrectStatus($releaseDate, $endDate, $today)
    {
        // Chuyển đổi thành chuỗi để so sánh
        $releaseDateStr = is_object($releaseDate) ? $releaseDate->toDateString() : $releaseDate;
        $endDateStr = $endDate ? (is_object($endDate) ? $endDate->toDateString() : $endDate) : null;
        
        if ($endDateStr && $endDateStr < $today) {
            return 'ended';
        } elseif ($releaseDateStr > $today) {
            return 'upcoming';
        } else {
            return 'showing';
        }
    }
}
