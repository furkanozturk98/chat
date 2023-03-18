<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatuses;
use App\Events\groupMessageDeleted;
use App\Events\groupMessageEdited;
use App\Events\groupMessageSeen;
use App\Events\groupMessageSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMessageEditFormRequest;
use App\Http\Resources\GroupMessageResource;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     * @return AnonymousResourceCollection
     */
    public function index(Group $group)
    {
        $this->updateMessagesAsRead($group);

        $messages = GroupMessage::withTrashed()
            ->where('group_id', $group->id)
            ->get();

        return GroupMessageResource::collection($messages);
    }

    public function updateMessagesAsRead(Group $group): void
    {
        $groupMember = GroupMember::query()
            ->where('group_id', $group->id)
            ->where('member_id', auth()->id())
            ->first();

        $messages = GroupMessageStatus::query()
            ->where('group_id', $group->id)
            ->where('member_id', $groupMember->id)
            ->where('status', MessageStatuses::UNREAD)
            ->get();

        if ($messages->isEmpty()) {
            return;
        }

        foreach ($messages as $message) {

            $message->update([
                'status' => MessageStatuses::READ,
            ]);

            broadcast(new groupMessageSeen($group->id, $message->message->sender, $message->message->id, auth()->id()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GroupMessageResource
     */
    public function store(Request $request, Group $group, GroupMember $groupMember)
    {
        $filename = null;
        if ($request->has('file')) {
            $filename =  time() . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('chat'), $filename);
        }

        /** @var GroupMessage $message */
        $message = GroupMessage::query()
            ->create([
                'group_id' => $group->id,
                'sender' => $groupMember->id,
                'content' => $request->input('message'),
                'image' => $filename,
                'created_at'
            ]);

        $groupMembers = GroupMember::query()
            ->where('group_id', $request->input('group_id'))
            ->get();

        $data=[];

        foreach ($groupMembers as $groupMember) {
            $data[] = [
                'group_id' => $groupMember->group_id,
                'member_id' => $groupMember->id,
                'message_id' => $message->id,
                'status' => $groupMember->id === auth()->id() ? MessageStatuses::READ : MessageStatuses::UNREAD
            ];
        }

        GroupMessageStatus::query()
            ->insert($data);

        broadcast(new groupMessageSend($message));

        return new GroupMessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param GroupMessage $groupMessage
     * @return \Illuminate\Http\Response
     */
    public function update(GroupMessageEditFormRequest $request, GroupMessage $groupMessage)
    {
        $attributes = $request->validated();

        $attributes['updated_at'] = now();

        $groupMessage->update($attributes);

        broadcast(new groupMessageEdited($groupMessage));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupMessage $groupMessage
     * @return \Illuminate\Http\Response
     */
    public function receive(GroupMessage $groupMessage)
    {
        $groupMember = GroupMember::query()
            ->where('group_id', $groupMessage->group_id)
            ->where('member_id', auth()->id())
            ->first();

        GroupMessageStatus::query()
            ->where('group_id', $groupMessage->group_id)
            ->where('member_id', $groupMember->id)
            ->where('message_id', $groupMessage->id)
            ->update([
                'status' => MessageStatuses::READ
            ]);

        broadcast(new groupMessageSeen($groupMessage->group_id, $groupMessage->sender, $groupMessage->id, auth()->id()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GroupMessage $groupMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(GroupMessage $groupMessage)
    {
        $messageId = $groupMessage->id;
        $groupId = $groupMessage->group_id;

        $groupMember = GroupMember::query()
            ->where('group_id', $groupMessage->group_id)
            ->where('member_id', auth()->id())
            ->first();

        $groupMessage->update([
            'deleted_by' => $groupMember->id
        ]);

        $groupMessage->delete();

        broadcast(new groupMessageDeleted($messageId, $groupId, $groupMember->id));

        return response()->json([
            'data' => $groupMember->id
        ]);
    }
}
