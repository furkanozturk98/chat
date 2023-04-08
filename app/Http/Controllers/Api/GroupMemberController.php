<?php

namespace App\Http\Controllers\Api;

use App\Enums\FriendRequestStatuses;
use App\Enums\GroupMemberTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMemberIndexRequest;
use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\GroupMemberResource;
use App\Models\FriendRequest;
use App\Models\Group;
use App\Models\GroupMember;
use App\Services\GroupMemberService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMemberController extends Controller
{
    public function __construct(public GroupMemberService $groupMemberService)
    {
    }

    /**
     * @param GroupMemberIndexRequest $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(GroupMemberIndexRequest $request): AnonymousResourceCollection
    {
        $groupMembers = $this->groupMemberService->getGroupMembers($request->input('group_id'));

        return GroupMemberResource::collection($groupMembers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request     $request
     * @param GroupMember $groupMember
     *
     * @return FriendRequestResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, GroupMember $groupMember)
    {
        //@TODO REFACTOR

        $count = FriendRequest::query()
            ->where('from', auth()->id())
            ->where('to', $groupMember->member_id)
            ->count();

        if ($count > 0) {
            return \response()->json([
                'error' => 'Already a friendship request is sent to this user',
            ], 422);
        }

        $data = FriendRequest::query()->create([
            'from'   => auth()->id(),
            'to'     => $groupMember->member_id,
            'status' => FriendRequestStatuses::WAITING,
        ]);

        return new FriendRequestResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     *
     * @return AnonymousResourceCollection
     */
    public function show(Group $group): AnonymousResourceCollection
    {
        $groupMembers = $this->groupMemberService->getGroupMembers($group->id);

        return GroupMemberResource::collection($groupMembers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMember $groupMember
     *
     * @return GroupMemberResource
     */
    public function makeAdmin(GroupMember $groupMember): GroupMemberResource
    {
        $groupMember->update([
            'type' => GroupMemberTypes::ADMIN,
        ]);

        return new GroupMemberResource($groupMember);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMember $groupMember
     *
     * @return GroupMemberResource
     */
    public function dismiss(GroupMember $groupMember): GroupMemberResource
    {
        $groupMember->update([
            'type' => GroupMemberTypes::USER,
        ]);

        return new GroupMemberResource($groupMember);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GroupMember $groupMember
     *
     * @return GroupMemberResource
     *
     * @throws \Exception
     */
    public function destroy(GroupMember $groupMember)
    {
        $memberCount = $this->groupMemberService->getGroupMemberCount($groupMember->id);

        $model = $groupMember;

        $groupMember->delete();

        if ($memberCount === 1) {
            Group::query()
                ->where('id', $groupMember->group_id)
                ->forceDelete();

            return new GroupMemberResource($model);
        }

        if ($groupMember->type === GroupMemberTypes::SUPER_ADMIN) {
            GroupMember::query()
                ->where('group_id', $groupMember->group_id)
                ->first()
                ->update([
                    'type' => GroupMemberTypes::SUPER_ADMIN,
                ]);
        }

        return new GroupMemberResource($model);
    }
}
