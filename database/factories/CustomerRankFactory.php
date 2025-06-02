<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomerRank;

class CustomerRankFactory extends Factory
{
    protected $model = CustomerRank::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'Rank-' . $index++,
            'min_points_required' => $this->faker->numberBetween(100, 1000),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 20),
            'description' => $this->faker->sentence,
        ];
    }
}