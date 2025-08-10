<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Promotion;
use App\Enums\PromotionDiscountType;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition()
    {
        $start_date = $this->faker->dateTimeThisYear;
        $end_date = (clone $start_date)->modify('+1 month');

        return [
            'name' => $this->faker->sentence,
            'code' => $this->faker->unique()->lexify('PROMO-????'),
            'description' => $this->faker->paragraph,
            'discount_type' => $this->faker->randomElement(PromotionDiscountType::cases()),
            'discount_value' => $this->faker->randomFloat(2, 5, 50),
            'max_discount_amount' => $this->faker->randomFloat(2, 10, 100),
            'min_booking_value' => $this->faker->randomFloat(2, 50, 200),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'quantity' => $this->faker->numberBetween(10, 100),
            'usage_limit_per_user' => $this->faker->numberBetween(1, 5),
            'applies_to' => $this->faker->randomElement(['movies', 'tickets', 'products']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}