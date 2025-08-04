<?php

namespace App\Services;

use App\Models\Showtime;
use App\Enums\ShowtimeStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ShowtimeStatusService
{
    /**
     * Cập nhật trạng thái suất chiếu dựa trên thời gian hiện tại
     */
    public function updateShowtimeStatuses(): int
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $updatedCount = 0;

        try {
            // Cập nhật từ scheduled sang ongoing
            $scheduledToOngoing = Showtime::where('status', ShowtimeStatus::Scheduled)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>', $now)
                ->count();

            if ($scheduledToOngoing > 0) {
                Showtime::where('status', ShowtimeStatus::Scheduled)
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>', $now)
                    ->update(['status' => ShowtimeStatus::Ongoing]);
                
                $updatedCount += $scheduledToOngoing;
                Log::info("Đã cập nhật {$scheduledToOngoing} suất chiếu từ 'scheduled' sang 'ongoing'");
            }

            // Cập nhật từ ongoing sang completed
            $ongoingToCompleted = Showtime::where('status', ShowtimeStatus::Ongoing)
                ->where('end_time', '<=', $now)
                ->count();

            if ($ongoingToCompleted > 0) {
                Showtime::where('status', ShowtimeStatus::Ongoing)
                    ->where('end_time', '<=', $now)
                    ->update(['status' => ShowtimeStatus::Completed]);
                
                $updatedCount += $ongoingToCompleted;
                Log::info("Đã cập nhật {$ongoingToCompleted} suất chiếu từ 'ongoing' sang 'completed'");
            }

            // Cập nhật từ scheduled sang completed (trường hợp bỏ qua ongoing)
            $scheduledToCompleted = Showtime::where('status', ShowtimeStatus::Scheduled)
                ->where('end_time', '<=', $now)
                ->count();

            if ($scheduledToCompleted > 0) {
                Showtime::where('status', ShowtimeStatus::Scheduled)
                    ->where('end_time', '<=', $now)
                    ->update(['status' => ShowtimeStatus::Completed]);
                
                $updatedCount += $scheduledToCompleted;
                Log::info("Đã cập nhật {$scheduledToCompleted} suất chiếu từ 'scheduled' sang 'completed'");
            }

            return $updatedCount;

        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật trạng thái suất chiếu: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Kiểm tra và cập nhật trạng thái cho một suất chiếu cụ thể
     */
    public function updateSingleShowtimeStatus(Showtime $showtime): bool
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $originalStatus = $showtime->status;

        try {
            if ($showtime->status === ShowtimeStatus::Scheduled) {
                if ($now->gte($showtime->end_time)) {
                    // Đã kết thúc -> completed
                    $showtime->update(['status' => ShowtimeStatus::Completed]);
                    Log::info("Suất chiếu ID {$showtime->getKey()} đã chuyển từ 'scheduled' sang 'completed'");
                    return true;
                } elseif ($now->gte($showtime->start_time)) {
                    // Đang chiếu -> ongoing
                    $showtime->update(['status' => ShowtimeStatus::Ongoing]);
                    Log::info("Suất chiếu ID {$showtime->getKey()} đã chuyển từ 'scheduled' sang 'ongoing'");
                    return true;
                }
            } elseif ($showtime->status === ShowtimeStatus::Ongoing) {
                if ($now->gte($showtime->end_time)) {
                    // Đã kết thúc -> completed
                    $showtime->update(['status' => ShowtimeStatus::Completed]);
                    Log::info("Suất chiếu ID {$showtime->getKey()} đã chuyển từ 'ongoing' sang 'completed'");
                    return true;
                }
            }

            return false; // Không có thay đổi

        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật trạng thái suất chiếu ID {$showtime->getKey()}: " . $e->getMessage());
            throw $e;
        }
    }
}
