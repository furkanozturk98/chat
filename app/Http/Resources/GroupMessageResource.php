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
            'sender' => $this->user,
            'message' => $this->content
        ];
    }
}
