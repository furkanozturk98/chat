<?php

namespace Tests\Feature\Api\GroupInvite;

use App\Enums\GroupInviteStatuses;
use App\Models\GroupInvite;
use Tests\TestCase;

class GroupInviteRejectTest extends TestCase
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
        $this->putJson($this->url() . '1/reject')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_rejects_a_group_invite()
    {
        $user = $this->getUser();

        /** @var GroupInvite $groupInvite */
        $groupInvite = GroupInvite::factory()->create([
            'from'   => $user,
            'status' => GroupInviteStatuses::WAITING,
        ]);

        $this->actingAs($user, 'api')
            ->putJson($this->url() . $groupInvite->id . '/reject')
            ->assertNoContent();

        $this->assertDatabaseHas('group_invites', [
            'id'     => $groupInvite->id,
            'status' => GroupInviteStatuses::REJECT,
        ]);
    }
}
