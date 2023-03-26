<?php

namespace App\Services;

use App\Models\GroupMember;

class GroupMemberService
{
    /**
     * @param string $groupId
     * @param string $memberId
     *
     * @return GroupMember
     */
    public function createMember(string $groupId, string $memberId): GroupMember
    {
        return GroupMember::query()
            ->create([
                'group_id'  => $groupId,
                'member_id' => $memberId,
            ]);
    }
}
