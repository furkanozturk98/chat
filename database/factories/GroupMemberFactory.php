<?php

namespace Database\Factories;

use App\Enums\GroupMemberTypes;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupMember>
 */
class GroupMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'group_id'  => Group::factory()->create()->id,
            'member_id' => User::factory()->create()->id,
            'type'      => rand(GroupMemberTypes::USER, GroupMemberTypes::SUPER_ADMIN),
        ];
    }
}
