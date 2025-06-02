<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SeatType;

class SeatTypeFactory extends Factory
{
    protected $model = SeatType::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'SeatType-' . $index++,
            'price_modifier' => $this->faker->randomFloat(2, 0, 50),
            'color_code' => $this->faker->hexColor,
            'description' => $this->faker->sentence,
        ];
    }
}