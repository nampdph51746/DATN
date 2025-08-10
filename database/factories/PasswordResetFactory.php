<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PasswordReset;

class PasswordResetFactory extends Factory
{
    protected $model = PasswordReset::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'token' => $this->faker->sha256,
            'created_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}