<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cập nhật trạng thái suất chiếu mỗi phút
        $schedule->command('showtime:update-status')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Dispatch job cập nhật trạng thái mỗi 2 phút
        $schedule->job(new \App\Jobs\UpdateShowtimeStatusJob)
                 ->everyTwoMinutes()
                 ->withoutOverlapping();

        // Cập nhật trạng thái phim đã hết hạn mỗi ngày lúc 00:00
        $schedule->command('movies:update-expired')
                 ->daily()
                 ->withoutOverlapping();

        // Xử lý booking attempts timeout mỗi 5 phút
        $schedule->command('booking:process-timeouts')
                 ->everyFiveMinutes()
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}