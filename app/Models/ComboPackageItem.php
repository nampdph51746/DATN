<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboPackageItem extends Model
{
    use HasFactory;

    protected $fillable = ['combo_product_variant_id', 'item_product_variant_id', 'quantity'];

    public function comboProductVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'combo_product_variant_id');
    }

    public function itemProductVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'item_product_variant_id');
    }
}