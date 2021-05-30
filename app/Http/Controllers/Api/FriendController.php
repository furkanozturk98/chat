<?php

namespace App\Http\Controllers\api;

use App\Events\userBlocked;
use App\Friend;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function block(Request $request, User $user)
    {
        Friend::query()
            ->where(function ($query) use ($user) {
                $query->whereIn('user_id', [$user->id, auth()->id()])
                    ->whereIn('friend_id', [$user->id, auth()->id()]);
            })
            ->update([
                'blocked_by' => auth()->id()
            ]);

        broadcast(new userBlocked($user, auth()->id()));

        return response()->json([
            'data' => [
                'blocked_by' => auth()->id()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblock(Request $request, User $user)
    {
        Friend::query()
            ->where(function ($query) use ($user) {
                $query->whereIn('user_id', [$user->id, auth()->id()])
                    ->whereIn('friend_id', [$user->id, auth()->id()]);
            })
            ->update([
                'blocked_by' => null
            ]);

        broadcast(new userBlocked($user, null));

        return response()->json([
            'data' => [
                'blocked_by' => null
            ]
        ]);
    }
}
