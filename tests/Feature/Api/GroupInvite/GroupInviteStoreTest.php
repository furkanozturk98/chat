<?php

namespace Tests\Feature\Api\GroupInvite;

use App\Enums\GroupInviteStatuses;
use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\GroupMember;
use App\Models\User;
use App\Rules\ExistingGroupInvite;
use App\Rules\ExistingMember;
use App\Rules\FriendRequest\ValidUser;
use Tests\TestCase;

class GroupInviteStoreTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/group-invites/';
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
                'group_id' => trans('validation.required', ['attribute' => 'group id']),
                'email'    => trans('validation.required', ['attribute' => 'email']),
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
    public function it_validates_that_you_cannot_send_group_invite_to_a_existing_group_member()
    {
        $user = $this->getUser();

        /** @var GroupMember $groupMember */
        $groupMember = GroupMember::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email'    => $groupMember->member->email,
                'group_id' => $groupMember->group_id,
            ])
            ->assertInvalid([
                'email' => (new ExistingMember())->message(),
            ]);
    }

    /** @test */
    public function it_validates_that_you_cannot_send_group_invite_if_group_invite_exist()
    {
        $user = $this->getUser();

        /** @var User $to */
        $to = User::factory()->create();

        /** @var GroupInvite $invite */
        $invite = GroupInvite::factory()->create([
            'from'   => $user->id,
            'to'     => $to->id,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email'    => $to->email,
                'group_id' => $invite->group_id,
            ])
            ->assertInvalid([
                'email' => (new ExistingGroupInvite())->message(),
            ]);
    }

    /** @test */
    public function it_create_group_invite()
    {
        $user = $this->getUser();

        $secondUser = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson($this->url(), [
                'email'    => $secondUser->email,
                'group_id' => Group::factory()->create()->id,
            ])
            ->assertSuccessful();
    }
}
