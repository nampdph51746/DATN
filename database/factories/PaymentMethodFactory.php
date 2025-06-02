<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'PaymentMethod-' . $index,
            'code' => 'PM-' . $index++,
            'is_active' => $this->faker->boolean,
            'logo_url' => $this->faker->imageUrl(),
        ];
    }
}