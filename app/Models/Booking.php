<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'booking_code', 'total_amount_before_discount', 'discount_amount',
        'final_amount', 'promotion_id', 'payment_method_id', 'status', 'notes',
        'customer_name', 'customer_phone', 'customer_email'
    ];

    protected $casts = [
        'total_amount_before_discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'status' => \App\Enums\BookingStatus::class,
    ];

    /**
     * Get total amount attribute
     */
    public function getTotalAmountAttribute()
    {
        return $this->final_amount;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function showtimeSeatStates()
    {
        return $this->hasMany(ShowtimeSeatState::class);
    }

    public function pointHistory()
    {
        return $this->hasMany(PointHistory::class);
    }

    /**
     * Get showtime through tickets
     * Lấy showtime từ vé đầu tiên (vì tất cả vé trong 1 booking thuộc cùng 1 showtime)
     */
    public function showtime()
    {
        return $this->hasOneThrough(
            Showtime::class,
            Ticket::class,
            'booking_id', // Foreign key on tickets table
            'id', // Foreign key on showtimes table  
            'id', // Local key on bookings table
            'showtime_id' // Local key on tickets table
        );
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class, 'booking_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
}