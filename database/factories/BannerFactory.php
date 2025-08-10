<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Banner;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'image_url' => $this->faker->imageUrl(),
            'link_url' => $this->faker->url,
            'display_order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean,
            'start_date' => $this->faker->dateTimeThisYear,
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}