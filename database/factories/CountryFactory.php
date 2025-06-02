<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'Country-' . $index,
            'code' => 'C-' . $index++,
        ];
    }
}