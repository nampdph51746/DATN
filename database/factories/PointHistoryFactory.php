<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PointHistory;
use App\Enums\PointReasonType;

class PointHistoryFactory extends Factory
{
    protected $model = PointHistory::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'booking_id' => \App\Models\Booking::factory(),
            'points_change' => $this->faker->numberBetween(-100, 100),
            'reason_type' => $this->faker->randomElement(PointReasonType::cases()),
            'description' => $this->faker->sentence,
        ];
    }
}