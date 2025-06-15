<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        return [
            'cinema_id' => \App\Models\Cinema::factory(),
            'room_type_id' => \App\Models\RoomType::factory(),
            'name' => $this->faker->word,
            'capacity' => $this->faker->numberBetween(50, 200),
            'status' => $this->faker->randomElement(['active', 'maintenance']),
        ];
    }
}