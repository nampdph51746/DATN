<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku', 'price', 'stock_quantity', 'image_url', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariantOptions()
    {
        return $this->hasMany(ProductVariantOption::class);
    }

    public function comboPackageItems()
    {
        return $this->hasMany(ComboPackageItem::class, 'combo_product_variant_id');
    }

    public function comboPackageItemItems()
    {
        return $this->hasMany(ComboPackageItem::class, 'item_product_variant_id');
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }
}