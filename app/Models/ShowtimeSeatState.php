<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowtimeSeatState extends Model
{
    use HasFactory;

    protected $fillable = [
        'showtime_id',
        'seat_id',
        'status',
        'locked_until',
        'booking_id',
        'locked_by',
    ];

    protected $casts = [
        'status' => \App\Enums\SeatStatus::class,
        'locked_until' => 'datetime',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}