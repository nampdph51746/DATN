<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Enums\BookingStatus;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'booking_code' => $this->faker->unique()->lexify('BOOK-?????'),
            'total_amount_before_discount' => $this->faker->randomFloat(2, 50, 500),
            'discount_amount' => $this->faker->randomFloat(2, 0, 50),
            'final_amount' => $this->faker->randomFloat(2, 50, 500),
            'promotion_id' => \App\Models\Promotion::factory(),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'status' => $this->faker->randomElement(BookingStatus::cases()),
            'notes' => $this->faker->paragraph,
        ];
    }
}