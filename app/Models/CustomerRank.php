<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRank extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'min_points_required', 'discount_percentage', 'description'];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customerRankPromotions()
    {
        return $this->hasMany(CustomerRankPromotion::class);
    }
}