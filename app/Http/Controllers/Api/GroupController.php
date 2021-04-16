<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateFormRequest;
use App\Http\Resources\GroupResource;
use App\MessageStatuses;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use Illuminate\Http\Request;
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
            ->pluck(['id']);

        $groups = Group::query()
            ->whereIn('id', $groupIds)
            ->get();

        $groups->map(function($group) {
            $count = GroupMessageStatus::query()
                ->where('group_id',auth()->id())
                ->where('member_id',auth()->id())
                ->where('status', MessageStatuses::UNREAD)
                ->count();

            $group->unread = $count;
        });

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupCreateFormRequest $request
     * @return GroupResource
     */
    public function store(GroupCreateFormRequest $request)
    {
        $attributes = [
            'name' => $request->input('name'),
            'created_by' => $request->user()->id
        ];

        /** @var Group $group */
        $group = Group::query()
            ->create($attributes);

        GroupMember::query()
            ->create([
                'group_id' => $group->id,
                'member_id' => $request->user()->id
            ]);

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
