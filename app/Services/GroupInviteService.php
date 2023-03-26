<?php

namespace App\Services;

use App\Enums\GroupInviteStatuses;
use App\Models\GroupInvite;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
     * @param User $user
     *
     * @return GroupInvite
     */
    public function createInvite(User $user): GroupInvite
    {
        return GroupInvite::query()->create([
            'from'   => auth()->id(),
            'to'     => $user->id,
            'status' => GroupInviteStatuses::WAITING,
        ]);
    }
}
