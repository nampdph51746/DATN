<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'room_id', 'start_time', 'end_time', 'base_price', 'status'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'base_price' => 'decimal:2',
        'status' => \App\Enums\ShowtimeStatus::class,
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
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