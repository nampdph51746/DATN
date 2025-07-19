<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRankPromotion extends Model
{
    use HasFactory;

    protected $table = 'customer_rank_promotions';
    protected $primaryKey = ['customer_rank_id', 'promotion_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['customer_rank_id', 'promotion_id', 'description'];

    public function customerRank()
    {
        return $this->belongsTo(CustomerRank::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}