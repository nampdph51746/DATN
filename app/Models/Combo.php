<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = [
        'name',
        'combo_product_variant_id',
        'stock_quantity',
        'price',
    ];

    public function comboProductVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'combo_product_variant_id');
    }

    public function comboPackageItems()
    {
        return $this->hasMany(ComboPackageItem::class, 'combo_id');
    }
}
