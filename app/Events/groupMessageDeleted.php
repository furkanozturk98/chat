<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class groupMessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $messageId;
    public int $groupId;
    public int $deletedBy;

    /**
     * Create a new event instance.
     * @param int $id
     *
     * @param User $user
     */
    public function __construct(int $messageId, int $groupId, int $deletedBy)
    {
        $this->messageId = $messageId;
        $this->groupId = $groupId;
        $this->deletedBy = $deletedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('groupMessages.'.$this->groupId);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->messageId,
            'deleted_by' => $this->deletedBy
        ];
    }
}
