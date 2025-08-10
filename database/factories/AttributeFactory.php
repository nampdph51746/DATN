<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attribute;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'Attribute-' . $index++,
        ];
    }
}