<?php

namespace App\Events;

use App\Http\Resources\GroupMessageResource;
use App\Models\GroupMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class groupMessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public GroupMessage $message;

    /**
     * Create a new event instance.
     *
     */
    public function __construct(GroupMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('groupMessages.'.$this->message->group_id);
    }

    public function broadcastWith()
    {
        return ['message' => new GroupMessageResource($this->message)];
    }
}
