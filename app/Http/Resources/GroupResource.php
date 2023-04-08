<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Group
 */
class GroupResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'image'        => $this->image,
            'unread'       => $this->unread,
            'last_message' => isset($this->lastMessage) ? $this->lastMessage->format('Y-m-d H:i') : null,
        ];
    }
}
