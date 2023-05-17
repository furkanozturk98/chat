<?php

namespace App\Http\Resources;

use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GroupMember
 */
class GroupMemberResource extends JsonResource
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
            'id'       => $this->id,
            'group_id' => $this->group->id,
            'member'   => [
                'id'    => $this->member->id,
                'name'  => $this->member->name,
                'image' => $this->member->image,
                'about' => $this->member->about,
            ],
            'type'       => $this->type,
            'created_at' => $this->created_at,
        ];
    }
}
