<?php

namespace Tests\Feature\Api\FriendRequest;

use App\Enums\FriendRequestStatuses;
use App\Models\FriendRequest;
use Tests\TestCase;

class FriendRequestApproveTest extends TestCase
{
    public function url(): string
    {
        return '/api/friend-requests/';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->putJson($this->url() . '1/approve')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_approves_a_friend_request()
    {
        $user = $this->getUser();

        /** @var FriendRequest $firstFriendRequest */
        $firstFriendRequest = FriendRequest::factory()->create([
            'from'   => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->putJson($this->url() . $firstFriendRequest->id . '/approve')
            ->assertSuccessful()
            ->assertExactJson([
                'status' => true,
            ]);

        $this->assertDatabaseHas('friend_requests', [
           'id'     => $firstFriendRequest->id,
           'status' => FriendRequestStatuses::APPROVED,
        ]);
    }
}
