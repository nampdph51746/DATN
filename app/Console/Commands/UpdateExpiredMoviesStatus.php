<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use App\Enums\MovieStatus;

class UpdateExpiredMoviesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái các phim đã kết thúc dựa trên ngày kết thúc';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra và cập nhật trạng thái phim...');

        // Lấy tất cả phim có ngày kết thúc đã qua và chưa có trạng thái 'ended'
        $expiredMovies = Movie::where('end_date', '<', now())
            ->where('status', '!=', MovieStatus::Ended)
            ->get();

        if ($expiredMovies->isEmpty()) {
            $this->info('Không có phim nào cần cập nhật trạng thái.');
            return 0;
        }

        $count = 0;
        foreach ($expiredMovies as $movie) {
            $movie->update(['status' => MovieStatus::Ended]);
            $count++;
            $this->line("- Cập nhật phim: {$movie->name}");
        }

        $this->info("✅ Đã cập nhật trạng thái cho {$count} phim thành 'Kết thúc'.");
        
        return 0;
    }
}