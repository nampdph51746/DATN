<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $dates = ['deleted_at'];

    // Relationship with movies
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    // Scope for active countries
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Check if country can be deleted
    public function canBeDeleted()
    {
        return $this->movies()->count() === 0;
    }
}