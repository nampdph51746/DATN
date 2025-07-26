<?php

namespace App\Jobs;

use App\Services\ShowtimeStatusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateShowtimeStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        try {
            $service = new ShowtimeStatusService();
            $updatedCount = $service->updateShowtimeStatuses();
            
            if ($updatedCount > 0) {
                Log::info("Job cập nhật trạng thái cho {$updatedCount} suất chiếu");
            }
        } catch (\Exception $e) {
            Log::error('Lỗi trong Job cập nhật trạng thái suất chiếu: ' . $e->getMessage());
            throw $e;
        }
    }
}
