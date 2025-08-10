<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomerRankPromotion;

class CustomerRankPromotionFactory extends Factory
{
    protected $model = CustomerRankPromotion::class;

    public function definition()
    {
        return [
            'customer_rank_id' => \App\Models\CustomerRank::factory(),
            'promotion_id' => \App\Models\Promotion::factory(),
            'description' => $this->faker->sentence,
        ];
    }
}