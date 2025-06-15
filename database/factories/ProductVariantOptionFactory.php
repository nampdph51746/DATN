<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductVariantOption;

class ProductVariantOptionFactory extends Factory
{
    protected $model = ProductVariantOption::class;

    public function definition()
    {
        return [
            'product_variant_id' => \App\Models\ProductVariant::factory(),
            'attribute_value_id' => \App\Models\AttributeValue::factory(),
        ];
    }
}