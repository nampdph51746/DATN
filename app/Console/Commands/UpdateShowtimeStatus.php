<?php

namespace App\Console\Commands;

use App\Services\ShowtimeStatusService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateShowtimeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showtime:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái suất chiếu tự động (scheduled -> ongoing -> completed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $service = new ShowtimeStatusService();
            $updatedCount = $service->updateShowtimeStatuses();

            if ($updatedCount > 0) {
                $this->info("Đã cập nhật trạng thái cho {$updatedCount} suất chiếu");
                Log::info("Command showtime:update-status đã cập nhật {$updatedCount} suất chiếu");
            } else {
                $this->info("Không có suất chiếu nào cần cập nhật trạng thái");
            }

        } catch (\Exception $e) {
            $this->error("Lỗi khi cập nhật trạng thái suất chiếu: " . $e->getMessage());
            Log::error("Lỗi trong command showtime:update-status: " . $e->getMessage());
        }
    }
}
