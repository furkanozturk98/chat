<?php

namespace Tests\Feature\Api\Group;

use App\Enums\MessageStatuses;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessageStatus;
use App\Models\Message;
use App\Models\User;
use Tests\TestCase;

class GroupIndexTest extends TestCase
{
    /**
     * @return string
     */
    public function url(): string
    {
        return '/api/groups';
    }

    /** @test */
    public function it_requires_authentication()
    {
        $this->getJson($this->url())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_group_list()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var Group $group */
        $group = Group::factory()->create();

        GroupMember::factory()->create([
           'member_id' => $firstUser->id,
           'group_id'  => $group->id,
        ]);

        $group->unread = 0;

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment((new GroupResource($group))->jsonSerialize());
    }

    /** @test */
    public function it_returns_group_list_with_unread_counts()
    {
        /** @var User $firstUser */
        $firstUser = User::factory()->create();

        /** @var Group $group */
        $group = Group::factory()->create();

        /** @var GroupMember $groupMember */
        $groupMember = GroupMember::factory()->create([
            'member_id' => $firstUser->id,
            'group_id'  => $group->id,
        ]);

        /** @var Message $message */
        $message = Message::factory()->create([
            'from'       => $firstUser->id,
            'group_id'   => $group->id,
            'created_at' => now(),
        ]);

        GroupMessageStatus::factory()->create([
           'group_id'   => $group->id,
           'member_id'  => $firstUser->id,
           'message_id' => $message->id,
           'status'     => MessageStatuses::UNREAD,
        ]);

        $group->unread      = 1;
        $group->lastMessage = $message->created_at;

        $this->actingAs($firstUser, 'api')
            ->getJson($this->url())
            ->assertSuccessful()
            ->assertJsonFragment((new GroupResource($group))->jsonSerialize());
    }
}
