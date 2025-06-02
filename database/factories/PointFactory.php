<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Point;

class PointFactory extends Factory
{
    protected $model = Point::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'total_points' => $this->faker->numberBetween(0, 1000),
            'points_expiry_date' => $this->faker->date('Y-m-d', '+1 year'),
        ];
    }
}