<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Movie;
use App\Enums\MovieStatus;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'director' => $this->faker->name,
            'actors' => $this->faker->paragraph,
            'duration_minutes' => $this->faker->numberBetween(90, 180),
            'release_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'description' => $this->faker->paragraph,
            'poster_url' => $this->faker->imageUrl(),
            'trailer_url' => $this->faker->url,
            'language' => $this->faker->languageCode,
            'country_id' => \App\Models\Country::factory(),
            'age_limit_id' => \App\Models\AgeLimit::factory(),
            'status' => $this->faker->randomElement(MovieStatus::cases()),
            'average_rating' => $this->faker->randomFloat(1, 0, 10),
        ];
    }
}