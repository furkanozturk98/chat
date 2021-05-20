<?php

use App\Models\GroupMember;
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

Broadcast::channel('messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('groupMessages.{groupId}', function ($user, $groupId) {

    $isMember = GroupMember::query()
        ->where('member_id', $user->id)
        ->where('group_id', $groupId)
        ->get();

    return isset($isMember);
});
