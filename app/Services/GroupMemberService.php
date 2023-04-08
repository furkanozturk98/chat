<?php

namespace App\Services;

use App\Models\GroupMember;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @param int $groupId
     *
     * @return GroupMember|null
     */
    public function getGroupMember(int $groupId): ?GroupMember
    {
        return GroupMember::query()
            ->where('group_id', $groupId)
            ->where('member_id', auth()->id())
            ->first();
    }

    /**
     * @param int $groupId
     *
     * @return Collection
     */
    public function getGroupMembers(int $groupId): Collection
    {
        return GroupMember::query()
            ->where('group_id', $groupId)
            ->get();
    }

    /**
     * @param int $groupId
     *
     * @return int
     */
    public function getGroupMemberCount(int $groupId): int
    {
        return GroupMember::query()
            ->where('group_id', $groupId)
            ->count();
    }
}
