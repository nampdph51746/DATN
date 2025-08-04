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

    }

    public function broadcastOn()
    {
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
        return $data;
    }

    public function broadcastAs()
    {
        return 'seat-status-updated';
    }
}