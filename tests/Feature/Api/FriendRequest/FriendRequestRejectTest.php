<?php

namespace Tests\Feature\Api\FriendRequest;

use App\Enums\FriendRequestStatuses;
use App\Models\FriendRequest;
use Tests\TestCase;

class FriendRequestRejectTest extends TestCase
{
    public function url(): string
    {
        return '/api/friend-requests/';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->putJson($this->url() . '1/reject')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_rejects_a_friend_request()
    {
        $user = $this->getUser();

        /** @var FriendRequest $firstFriendRequest */
        $firstFriendRequest = FriendRequest::factory()->create([
            'from'   => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->putJson($this->url() . $firstFriendRequest->id . '/reject')
            ->assertNoContent();

        $this->assertDatabaseMissing('friend_requests', [
            'id'     => $firstFriendRequest->id,
        ]);
    }
}
