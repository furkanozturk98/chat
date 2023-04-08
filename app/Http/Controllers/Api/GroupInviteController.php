<?php

namespace App\Http\Controllers\Api;

use App\Enums\GroupInviteStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\groupInviteFormRequest;
use App\Http\Resources\GroupInviteResource;
use App\Http\Resources\GroupMemberResource;
use App\Models\GroupInvite;
use App\Services\GroupInviteService;
use App\Services\GroupMemberService;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GroupInviteController extends Controller
{
    public function __construct(
        public GroupInviteService $groupInviteService,
        public GroupMemberService $groupMemberService,
        public UserService $userService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $groupInvites = $this->groupInviteService->getWaitingInvites();

        return GroupInviteResource::collection($groupInvites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupInviteFormRequest $request
     *
     * @return GroupInviteResource
     */
    public function store(GroupInviteFormRequest $request): GroupInviteResource
    {
        $user = $this->userService->findUserByEmail($request->input('email'));

        $groupInvite = $this->groupInviteService->createInvite($user->id, $request->input('group_id'));

        return new GroupInviteResource($groupInvite);
    }

    /**
     * @param GroupInvite $groupInvite
     *
     * @return GroupMemberResource
     */
    public function approve(GroupInvite $groupInvite): GroupMemberResource
    {
        $this->groupInviteService->updateInviteStatus(
            groupInvite: $groupInvite,
            status: GroupInviteStatuses::APPROVED
        );

        $groupMember = $this->groupMemberService->createMember(
            groupId: $groupInvite->group_id,
            memberId: $groupInvite->to,
        );

        return new GroupMemberResource($groupMember);
    }

    /**
     * @param GroupInvite $groupInvite
     *
     * @return Response
     */
    public function reject(GroupInvite $groupInvite): Response
    {
        $this->groupInviteService->updateInviteStatus(
            groupInvite: $groupInvite,
            status: GroupInviteStatuses::REJECT
        );

        return response()->noContent();
    }

    /**
     * @param GroupInvite $groupInvite
     *
     * @return Response
     */
    public function cancel(GroupInvite $groupInvite): Response
    {
        $this->groupInviteService->updateInviteStatus(
            groupInvite: $groupInvite,
            status: GroupInviteStatuses::CANCELLED
        );

        return response()->noContent();
    }
}
