<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cinema;
use App\Enums\CinemaStatus;

class CinemaFactory extends Factory
{
    protected $model = Cinema::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'city_id' => \App\Models\City::factory(),
            'hotline' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'map_url' => $this->faker->url,
            'image_url' => $this->faker->imageUrl(),
            'opening_hours' => '09:00-22:00',
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(CinemaStatus::cases()),
        ];
    }
}