<?php

namespace Tests\Feature\Api\FriendRequest;

use App\Enums\FriendRequestStatuses;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use Tests\TestCase;

class FriendRequestIndexTest extends TestCase
{
    public function url(): string
    {
        return '/api/friend-requests';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->getJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_list()
    {
        $user = $this->getUser();

        /** @var FriendRequest $firstFriendRequest */
        $firstFriendRequest = FriendRequest::factory()->create([
            'from'   => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        /** @var FriendRequest $secondFriendRequest */
        $secondFriendRequest = FriendRequest::factory()->create([
            'to'     => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        /** @var FriendRequest $other */
        $other = FriendRequest::factory()->create([
            'status' => FriendRequestStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment((new FriendRequestResource($firstFriendRequest))->jsonSerialize())
            ->assertJsonFragment((new FriendRequestResource($secondFriendRequest))->jsonSerialize())
            ->assertJsonMissing([
                'id' => $other->id,
            ]);
    }
}
