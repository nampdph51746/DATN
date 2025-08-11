<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relationship: ProductCategory hasMany Products
     * Sửa tên cột foreign key từ product_category_id thành category_id
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}