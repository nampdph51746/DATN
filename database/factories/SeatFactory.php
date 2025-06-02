<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seat;
use App\Enums\SeatStatus;

class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition()
    {
        return [
            'room_id' => \App\Models\Room::factory(),
            'seat_type_id' => \App\Models\SeatType::factory(),
            'row_char' => $this->faker->randomLetter,
            'seat_number' => $this->faker->numberBetween(1, 20),
            'status' => $this->faker->randomElement(SeatStatus::cases()),
        ];
    }
}