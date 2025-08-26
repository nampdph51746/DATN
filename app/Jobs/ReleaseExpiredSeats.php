<?php

namespace App\Jobs;

use App\Models\ShowtimeSeatState;
use App\Enums\SeatStatus;
use App\Events\SeatStatusUpdated;
use App\Events\SeatReleased;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReleaseExpiredSeats implements ShouldQueue
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
        Log::info('Starting ReleaseExpiredSeats job');

        // Tìm tất cả ghế đã hết hạn giữ chỗ (locked_until đã qua)
        $expiredSeats = ShowtimeSeatState::where('status', SeatStatus::Reserved)
            ->whereNotNull('locked_until')
            ->where('locked_until', '<', Carbon::now())
            ->get();

        if ($expiredSeats->isEmpty()) {
            Log::info('No expired seats found');
            return;
        }

        $releasedCount = 0;

        foreach ($expiredSeats as $seatState) {
            try {
                $oldStatus = $seatState->status;
                $oldLockedBy = $seatState->locked_by;
                
                // Release ghế
                $seatState->status = SeatStatus::Available;
                $seatState->locked_until = null;
                $seatState->locked_by = null;
                $seatState->save();

                // Broadcast events
                event(new SeatStatusUpdated(
                    $seatState->showtime_id, 
                    $seatState->seat_id, 
                    SeatStatus::Available, 
                    null, 
                    null
                ));
                
                event(new SeatReleased(
                    $seatState->showtime_id, 
                    $seatState->seat_id, 
                    'system_cleanup'
                ));

                $releasedCount++;
                
                Log::info("Released expired seat: showtime_id={$seatState->showtime_id}, seat_id={$seatState->seat_id}, was_locked_by={$oldLockedBy}");
                
            } catch (\Exception $e) {
                Log::error("Failed to release expired seat: showtime_id={$seatState->showtime_id}, seat_id={$seatState->seat_id}, error=" . $e->getMessage());
            }
        }

        Log::info("ReleaseExpiredSeats job completed. Released {$releasedCount} seats out of {$expiredSeats->count()} expired seats");
    }
}
