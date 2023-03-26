<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class messageSeen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $messageIds;
    public int $from;

    /**
     * Create a new event instance.
     *
     */
    public function __construct(int $from, Collection $messageIds)
    {
        $this->messageIds = $messageIds;
        $this->from = $from;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //dd('messages.'.$this->message->to);
        return new PrivateChannel('messageSeen.'.$this->from);
    }

    public function broadcastWith()
    {
        return ['messageIds' => $this->messageIds->toArray()];
    }
}
