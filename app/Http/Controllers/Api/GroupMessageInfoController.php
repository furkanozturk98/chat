<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMessageInfoResource;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMessageInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Group        $group
     * @param GroupMessage $groupMessage
     *
     * @return AnonymousResourceCollection
     */
    public function index(Group $group, GroupMessage $groupMessage): AnonymousResourceCollection
    {
        $groupMessageStatuses = GroupMessageStatus::query()
            ->where('group_id', $group->id)
            ->where('message_id', $groupMessage->id)
            ->where('member_id', '!=', $groupMessage->sender)
            ->get();

        return GroupMessageInfoResource::collection($groupMessageStatuses);
    }
}
