<?php

namespace Database\Factories;

use App\Enums\GroupInviteStatuses;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupInvite>
 */
class GroupInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'from'     => User::factory()->create()->id,
            'to'       => User::factory()->create()->id,
            'group_id' => Group::factory()->create()->id,
            'status'   => rand(GroupInviteStatuses::WAITING, GroupInviteStatuses::CANCELLED),
        ];
    }
}
