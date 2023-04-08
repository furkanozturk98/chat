<?php

namespace App\Services;

use App\Enums\GroupInviteStatuses;
use App\Models\GroupInvite;
use Illuminate\Database\Eloquent\Collection;

class GroupInviteService
{
    /**
     * @return Collection
     */
    public function getWaitingInvites(): Collection
    {
        return GroupInvite::query()
            ->where('from', auth()->id())
            ->orWhere('to', auth()->id())
            ->where('status', GroupInviteStatuses::WAITING)
            ->get();
    }

    /**
     * @param GroupInvite $groupInvite
     * @param int         $status
     *
     * @return void
     */
    public function updateInviteStatus(GroupInvite $groupInvite, int $status): void
    {
        $groupInvite->update([
            'status' => $status,
        ]);
    }

    /**
     * @param int $userId
     * @param int $groupId
     *
     * @return GroupInvite
     */
    public function createInvite(int $userId, int $groupId): GroupInvite
    {
        return GroupInvite::query()->create([
            'from'     => auth()->id(),
            'to'       => $userId,
            'group_id' => $groupId,
            'status'   => GroupInviteStatuses::WAITING,
        ]);
    }
}
