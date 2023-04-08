<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class messageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;

    public int $userId;

    /**
     * Create a new event instance.
     *
     * @param int  $id
     * @param User $user
     */
    public function __construct(int $id, int $userId)
    {
        $this->id = $id;

        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('messageDeleted.' . $this->userId);
    }

    public function broadcastWith()
    {
        return ['id' => $this->id];
    }
}
