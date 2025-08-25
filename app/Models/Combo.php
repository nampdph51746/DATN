<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = [
        'name',
        'combo_product_variant_id',
        'price',
        'stock_quantity',
        'combo_url',
    ];

    public function comboProductVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'combo_product_variant_id');
    }

    public function comboPackageItems()
    {
        return $this->hasMany(ComboPackageItem::class, 'combo_id');
    }

    // Quan hệ với Product thông qua ProductVariant
    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'combo_product_variant_id', 'product_id');
    }

    // Accessor để lấy SKU
    public function getSkuAttribute()
    {
        return $this->comboProductVariant ? $this->comboProductVariant->sku : 'N/A';
    }

    // Accessor để lấy product_id
    public function getProductIdAttribute()
    {
        return $this->comboProductVariant ? $this->comboProductVariant->product_id : null;
    }
}
