<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BookingItem;

class BookingItemFactory extends Factory
{
    protected $model = BookingItem::class;

    public function definition()
    {
        return [
            'booking_id' => \App\Models\Booking::factory(),
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'price_at_purchase' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}