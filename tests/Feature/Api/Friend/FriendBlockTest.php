<?php

namespace Tests\Feature\Api\Friend;

use App\Events\UserBlocked;
use App\Models\Friend;
use App\Models\User;
use Event;
use Tests\TestCase;

class FriendBlockTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/friends/';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->putJson($this->url() . '1/block')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_false_if_user_is_not_friend()
    {
        Event::fake();

        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var User $secondUser */
        $secondUser = User::factory()->create();

        $this
            ->actingAs($firstUser, 'api')
            ->putJson($this->url() . $secondUser->id . '/block')
            ->assertSuccessful()
            ->assertExactJson([
                'data' => [
                    'status' => false,
                ],
            ]);

        Event::assertNotDispatched(UserBlocked::class);

        $this->assertDatabaseMissing('friends', [
            'user_id'    => $secondUser->id,
            'friend_id'  => $firstUser->id,
            'blocked_by' => $firstUser->id,
        ]);
    }

    /** @test */
    public function it_blocks_a_friend()
    {
        Event::fake();

        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var User $secondUser */
        $secondUser = User::factory()->create();

        Friend::factory()->create([
            'user_id'   => $firstUser->id,
            'friend_id' => $secondUser->id,
        ]);

        Friend::factory()->create([
            'user_id'   => $secondUser->id,
            'friend_id' => $firstUser->id,
        ]);

        $this
            ->actingAs($firstUser, 'api')
            ->putJson($this->url() . $secondUser->id . '/block')
            ->assertSuccessful()
            ->assertExactJson([
                'data' => [
                    'status' => true,
                ],
            ]);

        Event::assertDispatched(
            event: UserBlocked::class,
            callback: fn (UserBlocked $event) => $event->user->id === $secondUser->id && $event->blockedBy === $firstUser->id
        );

        $this->assertDatabaseHas('friends', [
            'user_id'    => $secondUser->id,
            'friend_id'  => $firstUser->id,
            'blocked_by' => $firstUser->id,
        ]);
    }
}
