<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Enums\ProductType;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category_id' => \App\Models\ProductCategory::factory(),
            'name' => $this->faker->sentence(3),
            'sku' => $this->faker->unique()->lexify('???'),
            'description' => $this->faker->paragraph,
            'image_url' => $this->faker->imageUrl(),
            'product_type' => $this->faker->randomElement(ProductType::cases()),
            'is_active' => $this->faker->boolean,
        ];
    }
}