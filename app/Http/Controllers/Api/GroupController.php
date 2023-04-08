<?php

namespace App\Http\Controllers\Api;

use App\Enums\GroupMemberTypes;
use App\Enums\MessageStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateFormRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $groupIds = GroupMember::query()
            ->where('member_id', auth()->id())
            ->get()
            ->pluck(['group_id']);

        $groups = Group::query()
            ->whereIn('id', $groupIds)
            ->get();

        $groups->map(function($group) {
            $count = GroupMessageStatus::query()
                ->where('group_id', $group->id)
                ->where('member_id', auth()->id())
                ->where('status', MessageStatuses::UNREAD)
                ->count();

            $group->unread = $count;

            $lastMessage = GroupMessage::query()
                ->where('group_id', $group->id)
                ->latest()
                ->first();

            $group->lastMessage = $lastMessage->created_at ?? null;
        });

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupCreateFormRequest $request
     *
     * @return GroupResource
     */
    public function store(GroupCreateFormRequest $request)
    {
        $attributes = [
            'name'       => $request->input('name'),
            'created_by' => $request->user()->id,
            'image'      => 'group.png',
        ];

        /** @var Group $group */
        $group = Group::query()
            ->create($attributes);

        GroupMember::query()
            ->create([
                'group_id'  => $group->id,
                'member_id' => $request->user()->id,
                'type'      => GroupMemberTypes::SUPER_ADMIN,
            ]);

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
