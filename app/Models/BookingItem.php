<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'product_variant_id', 'quantity', 'price_at_purchase'];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}