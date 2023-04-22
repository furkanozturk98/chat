<?php

namespace Tests\Feature\Api\FriendRequest;

use App\Enums\FriendRequestStatuses;
use App\Models\FriendRequest;
use Tests\TestCase;

class FriendRequestCancelTest extends TestCase
{
    public function url(): string
    {
        return '/api/friend-requests/';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->deleteJson($this->url() . '1/cancel')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_cancels_a_friend_request()
    {
        $user = $this->getUser();

        /** @var FriendRequest $firstFriendRequest */
        $firstFriendRequest = FriendRequest::factory()->create([
            'from'   => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->deleteJson($this->url() . $firstFriendRequest->id . '/cancel')
            ->assertNoContent();

        $this->assertDatabaseMissing('friend_requests', [
            'id' => $firstFriendRequest->id,
        ]);
    }
}
