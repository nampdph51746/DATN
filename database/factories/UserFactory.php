<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'role_id' => \App\Models\Role::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'address' => $this->faker->address,
            'avatar_url' => $this->faker->imageUrl(),
            'date_of_birth' => $this->faker->date(),
            'status' => $this->faker->randomElement(UserStatus::cases()),
            'email_verified_at' => $this->faker->dateTimeThisYear,
            'last_login_at' => $this->faker->dateTimeThisMonth,
            'customer_rank_id' => \App\Models\CustomerRank::factory(),
        ];
    }
}