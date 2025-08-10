<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MovieGenre;

class MovieGenreFactory extends Factory
{
    protected $model = MovieGenre::class;

    public function definition()
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'genre_id' => \App\Models\Genre::factory(),
        ];
    }
}