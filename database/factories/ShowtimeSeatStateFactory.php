<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShowtimeSeatState;
use App\Enums\SeatStatus;

class ShowtimeSeatStateFactory extends Factory
{
    protected $model = ShowtimeSeatState::class;

    public function definition()
    {
        return [
            'showtime_id' => \App\Models\Showtime::factory(),
            'seat_id' => \App\Models\Seat::factory(),
            'status' => $this->faker->randomElement(SeatStatus::cases()),
            'locked_until' => $this->faker->dateTimeThisMonth,
            'booking_id' => \App\Models\Booking::factory(),
        ];
    }
}