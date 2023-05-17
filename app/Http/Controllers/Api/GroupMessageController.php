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
use App\Http\Resources\MessageResource;
use App\Models\Group;
use App\Models\GroupMessageStatus;
use App\Models\Message;
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

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupMemberStoreFormRequest $request
     *
     * @return MessageResource
     */
    public function store(GroupMemberStoreFormRequest $request): MessageResource
    {
        $message = $this->messageService->createGroupMessage(
            request: $request,
        );

        return new MessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMessageEditFormRequest $request
     * @param Message                     $message
     *
     * @return GroupMessageResource
     */
    public function update(GroupMessageEditFormRequest $request, Message $message): GroupMessageResource
    {
        $attributes = $request->validated();

        $attributes['updated_at'] = now();

        $message->update($attributes);

        broadcast(new GroupMessageEdited($message));

        return new GroupMessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Message $message
     *
     * @return void
     */
    public function receive(Message $message): void
    {
        abort_if(empty($message->group_id), 404);

        $groupMember = $this->groupMemberService->getGroupMember($message->group_id);

        GroupMessageStatus::query()
            ->where('group_id', $message->group_id)
            ->where('member_id', $groupMember->id)
            ->where('message_id', $message->id)
            ->update([
                'status' => MessageStatuses::READ,
            ]);

        broadcast(new GroupMessageSeen(
            $message->group_id,
            $message->from,
            $message->id,
            auth()->id()
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     *
     * @return JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        $messageId = $message->id;
        $groupId   = $message->group_id;

        $groupMember = $this->groupMemberService->getGroupMember($message->group_id);

        $message->update([
            'deleted_by' => $groupMember->member_id,
            'deleted_at' => now(),
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
