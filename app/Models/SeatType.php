<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price_modifier', 'color_code', 'description'];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}