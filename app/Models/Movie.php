<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'director', 'actors', 'duration_minutes', 'release_date',
        'end_date', 'description', 'poster_url', 'trailer_url', 'language',
        'country_id', 'age_limit_id', 'status', 'average_rating', 'image_path', 
    ];

    protected $casts = [
        'release_date' => 'date',
        'end_date' => 'date',
        'average_rating' => 'decimal:1',
        'status' => \App\Enums\MovieStatus::class,
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function ageLimit()
    {
        return $this->belongsTo(AgeLimit::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}