<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Enums\PaymentStatus;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'booking_id' => \App\Models\Booking::factory(),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'transaction_id_gateway' => $this->faker->unique()->uuid,
            'status' => $this->faker->randomElement(PaymentStatus::cases()),
            'payment_details' => json_encode(['note' => $this->faker->sentence]),
            'paid_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}