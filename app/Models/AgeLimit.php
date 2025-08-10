<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeLimit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'min_age'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}