<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatuses;
use App\Events\groupMessageDeleted;
use App\Events\groupMessageEdited;
use App\Events\groupMessageSeen;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMemberStoreFormRequest;
use App\Http\Requests\GroupMessageEditFormRequest;
use App\Http\Requests\GroupMessageIndexRequest;
use App\Http\Resources\GroupMessageResource;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use App\Services\GroupMemberService;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMessageController extends Controller
{
    public function __construct(
        public MessageService $messageService,
        public GroupMemberService $groupMemberService,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     *
     * @return AnonymousResourceCollection
     */
    public function index(GroupMessageIndexRequest $request): AnonymousResourceCollection
    {
        $groupId = $request->input('group_id');

        $this->messageService->updateGroupMessagesAsRead(groupId: $groupId);

        $messages = $this->messageService->getMessagesByGroupId(groupId: $groupId);

        return GroupMessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupMemberStoreFormRequest $request
     * @param Group                       $group
     * @param GroupMember                 $groupMember
     *
     * @return GroupMessageResource
     */
    public function store(GroupMemberStoreFormRequest $request, Group $group, GroupMember $groupMember): GroupMessageResource
    {
        $message = $this->messageService->createGroupMessage(
            request: $request,
            groupId: $group->id,
            groupMemberId: $groupMember->id
        );

        return new GroupMessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMessageEditFormRequest $request
     * @param GroupMessage                $groupMessage
     *
     * @return GroupMessageResource
     */
    public function update(GroupMessageEditFormRequest $request, GroupMessage $groupMessage): GroupMessageResource
    {
        $attributes = $request->validated();

        $attributes['updated_at'] = now();

        $groupMessage->update($attributes);

        broadcast(new GroupMessageEdited($groupMessage));

        return new GroupMessageResource($groupMessage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMessage $groupMessage
     *
     * @return void
     */
    public function receive(GroupMessage $groupMessage): void
    {
        $groupMember = $this->groupMemberService->getGroupMember($groupMessage->group_id);

        GroupMessageStatus::query()
            ->where('group_id', $groupMessage->group_id)
            ->where('member_id', $groupMember->id)
            ->where('message_id', $groupMessage->id)
            ->update([
                'status' => MessageStatuses::READ,
            ]);

        broadcast(new GroupMessageSeen(
            $groupMessage->group_id,
            $groupMessage->sender,
            $groupMessage->id,
            auth()->id()
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GroupMessage $groupMessage
     *
     * @return JsonResponse
     */
    public function destroy(GroupMessage $groupMessage): JsonResponse
    {
        $messageId = $groupMessage->id;
        $groupId   = $groupMessage->group_id;

        $groupMember = $this->groupMemberService->getGroupMember($groupMessage->group_id);

        $groupMessage->update([
            'deleted_by' => $groupMember->id,
            'deleted_at' >= now(),
        ]);

        broadcast(new GroupMessageDeleted(
            $messageId,
            $groupId,
            $groupMember->id
        ));

        return response()->json([
            'data' => $groupMember->id,
        ]);
    }
}
