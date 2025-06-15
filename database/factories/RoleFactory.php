<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        static $index = 1;
        return [
            'name' => 'Role-' . $index++,
            'description' => $this->faker->sentence,
        ];
    }
}