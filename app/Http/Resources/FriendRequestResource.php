<?php

namespace App\Http\Resources;

use App\Models\FriendRequest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FriendRequest
 */
class FriendRequestResource extends JsonResource
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
            'id'   => $this->id,
            'from' => [
                'id'    => $this->fromUser->id,
                'email' => $this->fromUser->email,
                'about' => $this->fromUser->about,
            ],
            'to' => [
                'id'    => $this->toUser->id,
                'email' => $this->toUser->email,
                'about' => $this->toUser->about,
            ],
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
