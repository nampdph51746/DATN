<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Genre;

class GenreFactory extends Factory
{
    protected $model = Genre::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'Genre-' . $index++,
            'description' => $this->faker->sentence,
        ];
    }
}