<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Enums\MovieStatus;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SimulateDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:simulate-date {date : Ngày muốn giả lập (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Giả lập ngày khác để test chức năng tự động cập nhật trạng thái phim';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $simulatedDate = $this->argument('date');
        
        try {
            $date = Carbon::parse($simulatedDate);
        } catch (\Exception $e) {
            $this->error('❌ Định dạng ngày không hợp lệ. Sử dụng: YYYY-MM-DD');
            return Command::FAILURE;
        }
        
        $this->info("🕰️  GIẢ LẬP NGÀY: {$simulatedDate}");
        $this->info('====================================');
        
        // Hiển thị trạng thái trước khi cập nhật
        $this->displayStatusBefore();
        
        // Thực hiện cập nhật với ngày giả lập
        $this->updateMovieStatusWithSimulatedDate($simulatedDate);
        
        // Hiển thị trạng thái sau khi cập nhật
        $this->displayStatusAfter();
        
        return Command::SUCCESS;
    }
    
    private function displayStatusBefore()
    {
        $this->info('📊 TRẠNG THÁI TRƯỚC KHI CẬP NHẬT:');
        
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
        $this->line('');
    }
    
    private function updateMovieStatusWithSimulatedDate($simulatedDate)
    {
        $this->info('🔄 ĐANG CẬP NHẬT TRẠNG THÁI VỚI NGÀY GIẢ LẬP...');
        
        // Cập nhật phim sắp chiếu -> đang chiếu
        $upcomingToShowing = Movie::where('status', MovieStatus::Upcoming)
                                  ->where('release_date', '<=', $simulatedDate)
                                  ->get();
                                  
        foreach ($upcomingToShowing as $movie) {
            $movie->update(['status' => MovieStatus::Showing]);
            $this->info("✅ {$movie->name}: upcoming -> showing");
        }
                                  
        // Cập nhật phim đang chiếu -> đã kết thúc
        $showingToEnded = Movie::where('status', MovieStatus::Showing)
                               ->whereNotNull('end_date')
                               ->where('end_date', '<', $simulatedDate)
                               ->get();
                               
        foreach ($showingToEnded as $movie) {
            $movie->update(['status' => MovieStatus::Ended]);
            $this->info("✅ {$movie->name}: showing -> ended");
        }
        
        $totalUpdated = $upcomingToShowing->count() + $showingToEnded->count();
        
        if ($totalUpdated > 0) {
            $this->info("🎉 Đã cập nhật {$totalUpdated} phim!");
        } else {
            $this->info("ℹ️  Không có phim nào cần cập nhật.");
        }
        $this->line('');
    }
    
    private function displayStatusAfter()
    {
        $this->info('📊 TRẠNG THÁI SAU KHI CẬP NHẬT:');
        
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
        
        $this->warn('⚠️  Lưu ý: Đây là test giả lập. Để hoàn tác về trạng thái đúng theo thời gian hiện tại,');
        $this->warn('     chạy lệnh: php artisan movies:reset-status');
    }
}
