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
            'id'         => $this->id,
            'from'       => $this->fromUser,
            'to'         => $this->toUser,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
