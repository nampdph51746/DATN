<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Thêm dòng này

class City extends Model
{
    use HasFactory, SoftDeletes; // <-- Thêm SoftDeletes

    protected $fillable = ['name', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class)->withTrashed();
    }

    public function cinemas()
    {
        return $this->hasMany(Cinema::class);
    }
}