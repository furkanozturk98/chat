<?php

namespace App\Http\Resources;

use App\Models\GroupMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Message
 */
class GroupMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'     => $this->id,
            'sender' => $this->from,
            //'type'       => $this->member->type,
            'group'      => $this->group_id,
            'message'    => $this->message,
            'image'      => $this->image,
            'created_at' => now()->format('Y-m-d H:i'),
            'updated_at' => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i') : null,
            'deleted_at' => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i') : null,
            'deleted_by' => $this->deleted_by,
        ];
    }
}
