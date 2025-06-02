<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'seat_type_id', 'row_char', 'seat_number', 'status'];

    protected $casts = [
        'status' => \App\Enums\SeatStatus::class,
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }

    public function showtimeSeatStates()
    {
        return $this->hasMany(ShowtimeSeatState::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}