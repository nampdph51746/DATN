<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'address', 'city_id', 'hotline', 'email', 'map_url',
        'image_url', 'opening_hours', 'description', 'status',
    ];

    protected $casts = [
        'status' => \App\Enums\CinemaStatus::class,
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}