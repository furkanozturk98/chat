<?php

namespace Database\Factories;

use App\Enums\MessageStatuses;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupMessageStatus>
 */
class GroupMessageStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_id'   => Group::factory()->create()->id,
            'member_id'  => User::factory()->create()->id,
            'message_id' => Message::factory()->create()->id,
            'status'     => rand(MessageStatuses::UNREAD, MessageStatuses::READ),
        ];
    }
}
