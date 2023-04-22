<?php

namespace Tests\Feature\Api\Friend;

use App\Enums\MessageStatuses;
use App\Http\Resources\FriendResource;
use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
use Tests\TestCase;

class FriendIndexTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/friends';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->getJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_friend_list()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var User $secondUser */
        $secondUser = User::factory()->create();

        $friend = Friend::factory()->create([
            'user_id'   => $firstUser->id,
            'friend_id' => $secondUser->id,
        ]);

        $friend->unread = 0;

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment((new FriendResource($friend))->jsonSerialize());
    }

    /** @test */
    public function it_returns_friend_list_with_unread_counts()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var User $secondUser */
        $secondUser = User::factory()->create();

        /** @var Friend $friend */
        $friend = Friend::factory()->create([
            'user_id'   => $firstUser->id,
            'friend_id' => $secondUser->id,
        ]);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from'       => $secondUser->id,
            'to'         => $firstUser->id,
            'room_id'    => $friend->room_id,
            'status'     => MessageStatuses::UNREAD,
            'created_at' => now(),
        ]);

        $friend->unread      = 1;
        $friend->lastMessage = $message->created_at;

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment((new FriendResource($friend))->jsonSerialize());
    }
}
