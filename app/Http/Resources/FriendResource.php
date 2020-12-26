<?php

namespace App\Http\Resources;

use App\Friend;
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
            'id' => $this->id,
            'user' => $this->user,
            'friend' => $this->friend,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
    }
}
