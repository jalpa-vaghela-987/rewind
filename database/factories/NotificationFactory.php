<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $sender_id = optional(User::query()->inRandomOrder()->first())->id;
        $receiver_id = optional(User::query()->where('id', '!=', $sender_id)->inRandomOrder()->first())->id;

        return [
            'sender_id'       => $sender_id,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $receiver_id,
            'data'            => [
                'message' => json_encode($this->faker->randomHtml),
            ],
            'type'            => "App\Notifications\SendMessageNotification",
        ];
    }
}
