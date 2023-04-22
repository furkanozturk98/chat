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
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            //'user'   => $this->user,
            'friend' => [
                'id'    => $this->friend->id,
                'name'  => $this->friend->name,
                'about' => $this->friend->about,
                'image' => $this->friend->image,
            ],
            'roomId'       => $this->room_id,
            'blocked_by'   => $this->blocked_by,
            'unread'       => $this->unread,
            'last_message' => isset($this->lastMessage) ? $this->lastMessage->format('Y-m-d H:i') : null,
        ];
    }
}
