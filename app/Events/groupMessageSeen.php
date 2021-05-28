<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class groupMessageSeen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $messageIds;
    public int $groupId;
    public int $memberId;

    /**
     * Create a new event instance.
     * @param int $groupId
     * @param int $memberId
     * @param Collection $messageIds
     */
    public function __construct(int $groupId, int $memberId, Collection $messageIds)
    {
        $this->groupId = $groupId;
        $this->memberId = $memberId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('groupMessageSeen.'.$this->groupId.$this->memberId);
    }

    public function broadcastWith()
    {
        return ['messageIds' => $this->messageIds->toArray()];
    }
}
