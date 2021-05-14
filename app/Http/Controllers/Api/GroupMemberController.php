<?php

namespace App\Http\Controllers\Api;

use App\FriendRequestStatuses;
use App\GroupMemberTypes;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\GroupMemberResource;
use App\Models\FriendRequest;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param GroupMember $groupMember
     * @return FriendRequestResource
     */
    public function store(Request $request, GroupMember $groupMember)
    {

        $data = FriendRequest::query()->create([
            'from' => auth()->id(),
            'to' => $groupMember->member_id,
            'status' => FriendRequestStatuses::WAITING
        ]);

        return new FriendRequestResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return AnonymousResourceCollection
     */
    public function show(Group $group): AnonymousResourceCollection
    {
        $groupMembers = GroupMember::query()
            ->where('group_id', $group->id)
            ->get();

        return GroupMemberResource::collection($groupMembers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param GroupMember $groupMember
     * @return Response
     */
    public function update(Request $request, GroupMember $groupMember)
    {
        $groupMember->update([
            'type' => GroupMemberTypes::ADMIN
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GroupMember $groupMember
     * @return Response
     * @throws \Exception
     */
    public function destroy(GroupMember $groupMember)
    {
        $memberCount = GroupMember::query()
            ->where('group_id', $groupMember->group_id)
            ->count();

        $groupId = $groupMember->group_id;

        if($memberCount === 1){

            $groupMember->delete();

            Group::query()
                ->where('id', $groupId)
                ->forceDelete();


            return \response()->noContent();
        }

        if($memberCount !== 1  && $groupMember->type === GroupMemberTypes::SUPER_ADMIN){

            $groupMember->delete();

            GroupMember::query()
                ->where('group_id', $groupMember->group_id)
                ->first()
                ->update([
                    'type' => GroupMemberTypes::SUPER_ADMIN
                ]);
        }

        $groupMember->delete();

        return \response()->noContent();
    }
}
