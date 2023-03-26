<?php

namespace App\Services;

use App\Enums\MessageStatuses;
use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
use DB;
use Illuminate\Support\Collection;

class FriendService
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $friends = $this->getFriends();

        $unreadMessages = $this->getUnreadMessages();

        return $friends->map(function($friend) use ($unreadMessages) {
            $friendUnreadMessages = $unreadMessages->where('sender_id', $friend->friend_id)->first();

            $friend->unread = $friendUnreadMessages ? $friendUnreadMessages->messages_count : 0;

            $lastMessage = $this->getLastMessage($friend);

            $friend->lastMessage = $lastMessage->created_at ?? null;

            return $friend;
        });
    }

    /**
     * @return Collection
     */
    public function getFriends(): Collection
    {
        return Friend::query()
            ->where('user_id', auth()->id())
            ->with('friend')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getUnreadMessages(): Collection
    {
        return Message::query()
            ->select(DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('status', MessageStatuses::UNREAD)
            ->groupBy('from')
            ->get();
    }

    /**
     * @param Friend $friend
     *
     * @return ?Message
     */
    public function getLastMessage(Friend $friend): ?Message
    {
        return Message::query()
            ->select([
                'created_at',
            ])
            ->whereIn('from', [$friend->friend_id, auth()->id()])
            ->whereIn('to', [$friend->friend_id, auth()->id()])
            ->latest()
            ->first();
    }

    /**
     * @param User $friend
     *
     * @return bool
     */
    public function block(User $friend): bool
    {
        return Friend::query()
            ->where(function($query) use ($friend) {
                $query->whereIn('user_id', [$friend->id, auth()->id()])
                    ->whereIn('friend_id', [$friend->id, auth()->id()]);
            })
            ->update([
                'blocked_by' => auth()->id(),
            ]);
    }

    /**
     * @param User $friend
     *
     * @return bool
     */
    public function unblock(User $friend): bool
    {
        return Friend::query()
            ->where(function($query) use ($friend) {
                $query->whereIn('user_id', [$friend->id, auth()->id()])
                    ->whereIn('friend_id', [$friend->id, auth()->id()]);
            })
            ->update([
                'blocked_by' => null,
            ]);
    }
}
