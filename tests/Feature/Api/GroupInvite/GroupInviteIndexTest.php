<?php

namespace Tests\Feature\Api\GroupInvite;

use App\Enums\GroupInviteStatuses;
use App\Models\GroupInvite;
use App\Models\User;
use Tests\TestCase;

class GroupInviteIndexTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/group-invites';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->getJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_cannot_see_group_invites_of_other_users()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var User $otherUser */
        $otherUser = User::factory()->create();

        $otherInvite = GroupInvite::factory()->create([
            'from'   => $otherUser->id,
            'to'     => 1,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $otherInvite2 = GroupInvite::factory()->create([
            'from'   => 1,
            'to'     => $otherUser->id,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonMissing([
                'id' => $otherInvite->id,
            ])
            ->assertJsonMissing([
                'id' => $otherInvite2->id,
            ]);
    }

    /** @test */
    public function it_see_only_waiting_invites()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        $invite = GroupInvite::factory()->create([
            'from'   => $firstUser->id,
            'to'     => 1,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $invite2 = GroupInvite::factory()->create([
            'from'   => 1,
            'to'     => $firstUser->id,
            'status' => GroupInviteStatuses::APPROVED,
        ]);

        $invite3 = GroupInvite::factory()->create([
            'from'   => 1,
            'to'     => $firstUser->id,
            'status' => GroupInviteStatuses::CANCELLED,
        ]);

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $invite->id,
            ])
            ->assertJsonMissing([
                'id' => $invite2->id,
            ])
            ->assertJsonMissing([
                'id' => $invite3->id,
            ]);
    }

    /** @test */
    public function it_return_group_invite_list()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        $invite = GroupInvite::factory()->create([
            'from'   => $firstUser->id,
            'to'     => 1,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $invite2 = GroupInvite::factory()->create([
            'from'   => 1,
            'to'     => $firstUser->id,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $invite->id,
            ])
            ->assertJsonFragment([
                'id' => $invite2->id,
            ]);
    }
}
