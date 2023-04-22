<?php

namespace Database\Factories;

use App\Enums\FriendRequestStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FriendRequest>
 */
class FriendRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'from'   => User::factory()->create()->id,
            'to'     => User::factory()->create()->id,
            'status' => rand(FriendRequestStatuses::WAITING, FriendRequestStatuses::APPROVED),
        ];
    }
}
