<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    use HasFactory;

    protected $table = 'product_variant_options';
    protected $primaryKey = ['product_variant_id', 'attribute_value_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['product_variant_id', 'attribute_value_id'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}