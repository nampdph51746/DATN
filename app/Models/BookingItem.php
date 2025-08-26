<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 
        'product_variant_id', 
        'quantity', 
        'price_at_purchase',
        'ticket_status',
        'product_status',
        'combo_id',
        'used_at',
        'scanned_by'
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'ticket_status' => TicketStatus::class,
        'product_status' => \App\Enums\ProductStatus::class,
        'used_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'product_id');
    }

    // Helper methods để phân biệt rõ ràng
    public function isCombo()
    {
        return !is_null($this->combo_id);
    }

    public function isRegularProduct()
    {
        return is_null($this->combo_id);
    }

    public function getItemName()
    {
        return $this->isCombo() ? $this->combo->name : $this->product->name;
    }

    public function getItemPrice()
    {
        return $this->isCombo() ? $this->combo->price : $this->productVariant->price;
    }
}