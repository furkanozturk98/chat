<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Resources\FriendResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function getfriendList(): AnonymousResourceCollection
    {

        /** @var Friend $friend */
        $friends = Friend::query()
            ->where('user_id',auth()->id())
            ->with('friend')
            ->get();

        return FriendResource::collection($friends);
    }
}
