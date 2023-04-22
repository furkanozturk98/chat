<?php

namespace Tests\Feature\Api\FriendRequest;

use App\Enums\FriendRequestStatuses;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use App\Rules\FriendRequest\ExistingFriend;
use App\Rules\FriendRequest\ExistingFriendRequest;
use App\Rules\FriendRequest\SendYourself;
use App\Rules\FriendRequest\ValidUser;
use Tests\TestCase;

class FriendRequestStoreTest extends TestCase
{
    public function url(): string
    {
        return '/api/friend-requests';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->postJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_validates_request()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url())
            ->assertStatus(422)
            ->assertInvalid([
                'user_id' => trans('validation.required', ['attribute' => 'user id']),
                'email'   => trans('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_validates_user_id_to_be_nullable_if_email_is_provided()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'email' => $user->email,
            ])
            ->assertCreated();
    }

    /** @test */
    public function it_validates_user()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'email' => 'test@test123123.com',
            ])
            ->assertInvalid([
                'email' => (new ValidUser())->message(),
            ]);
    }

    /** @test */
    public function it_validates_email()
    {
        $this->actingAs($this->getUser(), 'api')
            ->postJson($this->url(), [
                'email' => 'test@test',
            ])
            ->assertInvalid([
                'email' => trans('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_validates_that_you_cannot_send_friend_request_yourself()
    {
        $user = $this->getUser();

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email' => $user->email,
            ])
            ->assertInvalid([
                'email' => (new SendYourself())->message(),
            ]);
    }

    /** @test */
    public function it_validates_that_you_cannot_send_friend_request_to_your_friend()
    {
        $user = $this->getUser();

        /** @var Friend $friend */
        $friend = Friend::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email' => $friend->friend->email,
            ])
            ->assertInvalid([
                'email' => (new ExistingFriend())->message(),
            ]);
    }

    /** @test */
    public function it_validates_that_you_cannot_send_friend_request_again()
    {
        $user = $this->getUser();

        /** @var FriendRequest $firstFriendRequest */
        $firstFriendRequest = FriendRequest::factory()->create([
            'from'   => $user,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email' => $firstFriendRequest->toUser->email,
            ])
            ->assertInvalid([
                'email' => (new ExistingFriendRequest())->message(),
            ]);
    }

    /** @test */
    public function it_sends_friend_request()
    {
        $user = $this->getUser();

        /** @var User $secondUser */
        $secondUser = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email' => $secondUser->email,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('friend_requests', [
           'from'    => $user->id,
            'to'     => $secondUser->id,
            'status' => FriendRequestStatuses::WAITING,
        ]);
    }
}
