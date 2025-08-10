<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AgeLimit;

class AgeLimitFactory extends Factory
{
    protected $model = AgeLimit::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'AgeLimit-' . $index++,
            'description' => $this->faker->sentence,
            'min_age' => $this->faker->numberBetween(0, 18),
        ];
    }
}