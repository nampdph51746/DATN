<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'movie_id' => \App\Models\Movie::factory(),
            'rating_star' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}