<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country_id',
    ];

    protected $dates = ['deleted_at'];

    // Relationship with country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Relationship with cinemas
    public function cinemas()
    {
        return $this->hasMany(Cinema::class);
    }

    // Scope for active cities
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Check if city can be deleted
    public function canBeDeleted()
    {
        return $this->cinemas()->count() === 0;
    }
}