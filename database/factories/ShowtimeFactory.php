<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Showtime;
use App\Enums\ShowtimeStatus;

class ShowtimeFactory extends Factory
{
    protected $model = Showtime::class;

    public function definition()
    {
        $start_time = $this->faker->dateTimeBetween('now', '+1 month');
        $end_time = (clone $start_time)->modify('+2 hours');

        return [
            'movie_id' => \App\Models\Movie::factory(),
            'room_id' => \App\Models\Room::factory(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'base_price' => $this->faker->randomFloat(2, 50, 200),
            'status' => $this->faker->randomElement(ShowtimeStatus::cases()),
        ];
    }
}