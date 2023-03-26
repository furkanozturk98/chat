<?php

namespace App\Http\Resources;

use App\Models\Friend;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Friend
 */
class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'user' => $this->user,
            'friend' => $this->friend,
            'roomId' => $this->room_id,
            'blocked_by' => $this->blocked_by,
            'created_at' => $this->created_at,
            'unread' => $this->unread,
            'last_message' => isset($this->lastMessage) ? $this->lastMessage->format('Y-m-d H:i') : null
        ];
    }
}
