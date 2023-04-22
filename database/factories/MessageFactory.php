<?php

namespace Database\Factories;

use App\Enums\MessageStatuses;
use App\Models\Friend;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        /** @var Friend $friend */
        $friend = Friend::factory()->create();

        return [
            'from'    => $friend->user_id,
            'to'      => $friend->friend_id,
            'room_id' => $friend->room_id,
            'message' => \Str::random(30),
            'status'  => rand(MessageStatuses::UNREAD, MessageStatuses::READ),
        ];
    }
}
