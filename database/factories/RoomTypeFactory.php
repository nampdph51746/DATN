<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RoomType;

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'RoomType-' . $index++,
            'description' => $this->faker->sentence,
        ];
    }
}