<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  // thêm import SoftDeletes

class Country extends Model
{
    use HasFactory, SoftDeletes;  // thêm trait SoftDeletes

    protected $fillable = ['name', 'code'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}