<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GroupMessageSeen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $messageIds;

    public int $groupId;

    public int $senderId;

    public int $messageId;

    public int $receiverId;

    /**
     * Create a new event instance.
     *
     * @param int $groupId
     * @param int $senderId
     * @param int $messageId
     * @param int $receiverId
     */
    public function __construct(int $groupId, int $senderId, int $messageId, int $receiverId)
    {
        $this->groupId    = $groupId;
        $this->senderId   = $senderId;
        $this->messageId  = $messageId;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId . '.' . $this->senderId);
    }

    public function broadcastWith()
    {
        return [
            'groupId'    => $this->groupId,
            'messageId'  => $this->messageId,
            'receiverId' => $this->receiverId,
        ];
    }
}