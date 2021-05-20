<?php

namespace App\Http\Controllers\Api;

use App\Events\groupMessageSend;
use App\Events\messageSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMessageEditFormRequest;
use App\Http\Requests\MessageEditFormRequest;
use App\Http\Resources\GroupMessageResource;
use App\Http\Resources\GroupResource;
use App\MessageStatuses;
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
        GroupMessageStatus::query()
            ->where('group_id', $group->id)
            ->where('member_id', auth()->id())
            ->update([
                'status' => MessageStatuses::READ
            ]);

        $messages = GroupMessage::query()
            ->where('group_id', $group->id)
            ->get();

        return GroupMessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GroupMessageResource
     */
    public function store(Request $request)
    {

        /** @var GroupMessage $message */
        $message = GroupMessage::query()
            ->create([
                'group_id' => $request->input('group_id'),
                'sender' => $request->input('member_id'),
                'content' => $request->input('message'),
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
                'status' => MessageStatuses::UNREAD
            ];
        }

        GroupMessageStatus::query()
            ->insert($data);

        broadcast(new groupMessageSend($message));

        return new GroupMessageResource($message);

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
     * @param \Illuminate\Http\Request $request
     * @param GroupMessage $groupMessage
     * @return \Illuminate\Http\Response
     */
    public function update(GroupMessageEditFormRequest $request, GroupMessage $groupMessage)
    {
        $groupMessage->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GroupMessage $groupMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupMessage $groupMessage)
    {
        $groupMessage->forceDelete();

        return response(200);
    }
}
