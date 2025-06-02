<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductVariant;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'sku' => $this->faker->unique()->lexify('SKU-????'),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->imageUrl(),
            'is_active' => $this->faker->boolean,
        ];
    }
}