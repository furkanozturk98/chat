<?php

namespace App\Http\Resources;

use App\Models\GroupMessageStatus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GroupMessageStatus
 */
class GroupMessageInfoResource extends JsonResource
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
            'member' => $this->member->member,
            'group' => $this->group_id,
            'message' => $this->message,
            'status' => $this->status,
            'updated_at' => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i'): null,
        ];
    }
}
