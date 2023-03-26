<?php

namespace App\Http\Resources;

use App\Models\GroupInvite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GroupInvite
 */
class GroupInviteResource extends JsonResource
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
            'id'         => $this->id,
            'from'       => $this->fromUser,
            'to'         => $this->toUser,
            'group'      => $this->group,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
