<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_booking_value',
        'start_date',
        'end_date',
        'quantity',
        'usage_limit_per_user',
        'applies_to',
        'status',
        'rank_id',
    ];

    protected $casts = [
        'discount_type' => \App\Enums\PromotionDiscountType::class,
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_booking_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function customerRankPromotions()
    {
        return $this->hasMany(CustomerRankPromotion::class);
    }

    public function rank()
    {
        return $this->belongsTo(CustomerRank::class, 'rank_id');
    }
}
