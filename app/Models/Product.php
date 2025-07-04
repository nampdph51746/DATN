<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'image_url', 'product_type', 'is_active'];

    protected $casts = [
        'product_type' => \App\Enums\ProductType::class,
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    
}
