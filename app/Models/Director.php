<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'birth_date',
        'nationality',
        'image_path',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}