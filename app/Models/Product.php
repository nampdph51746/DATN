<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku', 
        'description',
        'image_url',
        'product_type',
        'is_active',
        'base_price',
        'created_at',
        'updated_at'
    ];

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

    // Kiểm tra xem sản phẩm có được đặt hay không
    public function hasBookings()
    {
        return $this->productVariants()
            ->whereHas('bookingItems', function($query) {
                $query->whereHas('booking', function($bookingQuery) {
                    $bookingQuery->whereIn('status', ['confirmed', 'completed']);
                });
            })
            ->exists();
    }

    // Kiểm tra xem sản phẩm có thể chỉnh sửa không
    public function canBeEdited()
    {
        // Không thể chỉnh sửa nếu sản phẩm đã có đơn đặt hàng
        return !$this->hasBookings();
    }
}