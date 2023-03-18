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

class userBlocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?int $blocked_by;

    /**
     * Create a new event instance.
     *
     * @param User $user
     *
     * @param int $blocked_by
     */
    public function __construct(User $user, ?int $blocked_by)
    {
        $this->user = $user;
        $this->blocked_by = $blocked_by;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('messages.'.$this->user->id);
    }

    public function broadcastWith()
    {
        return ['data' => ['blocked_by' => $this->blocked_by]];
    }
}
