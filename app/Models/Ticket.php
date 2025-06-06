<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'showtime_id', 'seat_id', 'ticket_code', 'price_at_purchase', 'status'];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'status' => \App\Enums\TicketStatus::class,
    ];

    public function tickets() {
    return $this->hasMany(Ticket::class, 'showtime_id');
}

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function showtimes() {
    return $this->hasMany(Showtime::class, 'movie_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}