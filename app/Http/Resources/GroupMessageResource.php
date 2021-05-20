<?php

namespace App\Http\Resources;

use App\Models\GroupMessage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GroupMessage
 */
class GroupMessageResource extends JsonResource
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
            'sender' => $this->member->member,
            'type' => $this->member->type,
            'group' => $this->member->group,
            'message' => $this->content,
            'created_at' => $this->created_at->format('Y-m-d H:i')
        ];
    }
}
