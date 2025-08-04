<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 
        'showtime_id', 
        'seat_id', 
        'ticket_code', 
        'price_at_purchase', 
        'status',
        'used_at',
        'scanned_by'
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'status' => \App\Enums\TicketStatus::class,
        'used_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    //Lấy attribute của movie
    public function getMovieAttribute()
    {
        return $this->showtime?->movie;
    }

    
}