<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Config;

class ConfigFactory extends Factory
{
    protected $model = Config::class;

    public function definition()
    {
        return [
            'config_key' => $this->faker->unique()->word,
            'config_value' => $this->faker->sentence,
            'description' => $this->faker->sentence,
        ];
    }
}