<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Enums\NotificationType;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(NotificationType::cases()),
            'link_url' => $this->faker->url,
            'is_read' => $this->faker->boolean,
            'read_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}