<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'payment_method_id', 'amount', 'transaction_id_gateway',
        'status', 'payment_details', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => \App\Enums\PaymentStatus::class,
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}