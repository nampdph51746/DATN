<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ComboPackageItem;

class ComboPackageItemFactory extends Factory
{
    protected $model = ComboPackageItem::class;

    public function definition()
    {
        return [
            'combo_product_variant_id' => \App\Models\ProductVariant::factory(),
            'item_product_variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}