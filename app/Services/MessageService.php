<?php

namespace App\Services;

use App\Enums\MessageStatuses;
use App\Events\GroupMessageSeen;
use App\Events\messageSeen;
use App\Events\messageSend;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MessageService
{
    /**
     * @param string $roomId
     *
     * @return Collection
     */
    public function getMessagesByRoomId(string $roomId): Collection
    {
        $this->updateMessagesAsRead($roomId);

        return Message::query()
            ->withTrashed()
            ->where('room_id', $roomId)
            ->get();
    }

    /**
     * @param string $groupId
     *
     * @return Collection
     */
    public function getMessagesByGroupId(string $groupId): Collection
    {
        return GroupMessage::withTrashed()
            ->where('group_id', $groupId)
            ->get();
    }

    /**
     * @param string|null $roomId
     * @param string|null $groupId
     *
     * @return void
     */
    public function updateMessagesAsRead(?string $roomId = null, ?string $groupId = null): void
    {
        $messages = Message::query()
            ->where('room_id', $roomId)
            ->where('to', auth()->id())
            ->where('status', MessageStatuses::UNREAD)
            ->get();

        if ($messages->isEmpty()) {
            return;
        }

        $from = $messages->first()->from;

        $messageIdList = $messages->pluck(['id']);

        broadcast(new MessageSeen($from, $messageIdList));

        foreach ($messages as $message) {
            $message->update([
                'status' => MessageStatuses::READ,
            ]);
        }
    }

    /**
     * @param string $groupId
     *
     * @return void
     */
    public function updateGroupMessagesAsRead(string $groupId): void
    {
        $groupMember = GroupMember::query()
            ->where('group_id', $groupId)
            ->where('member_id', auth()->id())
            ->first();

        $messages = GroupMessageStatus::query()
            ->where('group_id', $groupId)
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

            broadcast(new GroupMessageSeen($groupId, $message->message->sender, $message->message->id, auth()->id()));
        }
    }

    /**
     * @param Request $request
     * @param string  $roomId
     * @param User    $user
     *
     * @return Message
     */
    public function createMessageByRoomId(Request $request, string $roomId, User $user): Message
    {
        $filename = $this->uploadFile($request);

        $message = Message::query()
            ->create([
                'from'    => auth()->id(),
                'to'      => $user->id,
                'room_id' => $roomId,
                'message' => $request->input('message'),
                'image'   => $filename,
                'status'  => MessageStatuses::UNREAD,
            ]);

        broadcast(new MessageSend($message));

        return $message;
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    public function uploadFile(Request $request): ?string
    {
        $filename = null;

        if ($request->has('file')) {
            $filename = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('chat'), $filename);
        }

        return $filename;
    }

    /**
     * @param array   $attributes
     * @param Message $message
     *
     * @return void
     */
    public function updateMessage(array $attributes, Message $message): void
    {
        $attributes['updated_at'] = now();

        $message->update($attributes);
    }
}
