<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SeatStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtimeId;
    public $seatId;
    public $status;
    public $lockedUntil;
    public $lockedBy;

    public function __construct($showtimeId, $seatId, $status, $lockedUntil = null, $lockedBy = null)
    {
        $this->showtimeId = $showtimeId;
        $this->seatId = $seatId;
        $this->status = $status;
        $this->lockedUntil = $lockedUntil;
        $this->lockedBy = $lockedBy;

        Log::info('SeatStatusUpdated event constructed', [
            'showtime_id' => $showtimeId,
            'seat_id' => $seatId,
            'is_even' => (int)$seatId % 2 === 0 ? 'true' : 'false',
            'status' => $status,
            'locked_until' => $lockedUntil ? $lockedUntil->toDateTimeString() : null,
            'locked_by' => $lockedBy,
        ]);
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting SeatStatusUpdated on channel: showtime.' . $this->showtimeId);
        return new Channel('showtime.' . $this->showtimeId);
    }

    public function broadcastWith()
    {
        $data = [
            'seat_id' => $this->seatId,
            'status' => $this->status,
            'locked_until' => $this->lockedUntil ? $this->lockedUntil->toDateTimeString() : null,
            'locked_by' => $this->lockedBy,
        ];
        Log::info('SeatStatusUpdated broadcast data', $data);
        return $data;
    }

    public function broadcastAs()
    {
        return 'seat-status-updated';
    }
}