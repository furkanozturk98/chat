<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatuses;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Models\Friend;
use App\Models\Message;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {

        /** @var Friend $friend */
        $friends = Friend::query()
            ->where('user_id', auth()->id())
            ->with('friend')
            ->get();

        $unReadIds = Message::query()->select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('status', MessageStatuses::UNREAD)
            ->groupBy('from')
            ->get();

        $friends->map(function ($friend) use ($unReadIds) {
            $friendUnread = $unReadIds->where('sender_id', $friend->friend_id)->first();

            $friend->unread = $friendUnread ? $friendUnread->messages_count : 0;

            $lastMessage = Message::query()
                ->whereIn('from', [$friend->friend_id, auth()->id()])
                ->whereIn('to', [$friend->friend_id, auth()->id()])
                ->latest()
                ->first();

            //dd($lastMessage->created_at);
            $friend->lastMessage = isset($lastMessage->created_at) ? $lastMessage->created_at : null;
        });

        return FriendResource::collection($friends);
    }
}
