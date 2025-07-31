<?php

namespace App\Observers;

use App\Models\Showtime;
use App\Services\ShowtimeStatusService;
use Illuminate\Support\Facades\Log;

class ShowtimeObserver
{
    /**
     * Handle the Showtime "retrieved" event.
     */
    public function retrieved(Showtime $showtime): void
    {
        // Tự động cập nhật trạng thái khi retrieve model
        $this->updateStatusIfNeeded($showtime);
    }

    /**
     * Handle the Showtime "created" event.
     */
    public function created(Showtime $showtime): void
    {
        Log::info("Suất chiếu mới được tạo: ID {$showtime->getKey()}, trạng thái: {$showtime->status->value}");
    }

    /**
     * Handle the Showtime "updated" event.
     */
    public function updated(Showtime $showtime): void
    {
        if ($showtime->isDirty('status')) {
            $originalStatus = $showtime->getOriginal('status');
            // Nếu là Enum thì lấy value, nếu không thì giữ nguyên
            if ($originalStatus instanceof \App\Enums\ShowtimeStatus) {
                $originalStatus = $originalStatus->value;
            }
            $currentStatus = $showtime->status->value;
            Log::info("Trạng thái suất chiếu ID {$showtime->getKey()} đã thay đổi: {$originalStatus} -> {$currentStatus}");
        }
    }

    /**
     * Cập nhật trạng thái nếu cần
     */
    private function updateStatusIfNeeded(Showtime $showtime): void
    {
        try {
            $service = new ShowtimeStatusService();
            $updated = $service->updateSingleShowtimeStatus($showtime);
            
            if ($updated) {
                Log::info("Observer đã cập nhật trạng thái suất chiếu ID {$showtime->getKey()}");
            }
        } catch (\Exception $e) {
            Log::error("Lỗi Observer khi cập nhật trạng thái suất chiếu ID {$showtime->getKey()}: " . $e->getMessage());
        }
    }
}
