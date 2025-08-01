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

        // Dispatch job tự động xác nhận vé sau 10 phút
        $schedule->job(new \App\Jobs\AutoConfirmPendingBookings)
                 ->everyMinute()
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
