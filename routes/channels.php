<?php

use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('private.{id}', function(User $user, int $id) {
    return $user->id === $id;
});

Broadcast::channel('group.{groupId}', function(User $user, int $groupId) {
    $isMember = GroupMember::query()
        ->where('member_id', $user->id)
        ->where('group_id', $groupId)
        ->get();

    return isset($isMember);
});

Broadcast::channel('group.{groupId}.{userId}', function(User $user, int $groupId) {
    $isMember = GroupMember::query()
        ->where('member_id', $user->id)
        ->where('group_id', $groupId)
        ->get();

    return isset($isMember);
});
