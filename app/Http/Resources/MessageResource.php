<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'from_id'    => $this->from,
            'from_name'  => $this->fromUser->name,
            'from_type'  => $this->fromMember->type,
            'to'         => $this->to,
            'roomId'     => $this->room_id,
            'group_id'   => $this->group_id,
            'message'    => $this->message,
            'image'      => $this->image,
            'status'     => $this->status,
            'created_at' => now()->format('Y-m-d H:i'),
            'updated_at' => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i') : null,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i') : null,
        ];
    }
}
