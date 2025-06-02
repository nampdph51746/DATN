<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Slider;

class SliderFactory extends Factory
{
    protected $model = Slider::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image_url' => $this->faker->imageUrl(),
            'link_url' => $this->faker->url,
            'display_order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean,
        ];
    }
}