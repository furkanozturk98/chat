<?php

namespace App\Http\Controllers\Api;

use App\Events\messageDeleted;
use App\Events\messageEdited;
use App\Events\messageSeen;
use App\Events\messageSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageEditFormRequest;
use App\Http\Resources\MessageResource;
use App\MessageStatuses;
use App\Models\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $roomId
     * @return AnonymousResourceCollection
     */
    public function index(string $roomId)
    {
        $this->updateMessagesAsRead($roomId);

        $messages = Message::query()
            ->withTrashed()
            ->where('room_id', $roomId)
            ->get();

        return MessageResource::collection($messages);
    }

    public function updateMessagesAsRead(string $roomId): void
    {
        $from = null;

        $messages = Message::query()
            ->where('room_id', $roomId)
            ->where('to', auth()->id())
            ->where('status', MessageStatuses::UNREAD)
            ->get();

        if ($messages->isEmpty()) {
            return;
        }

        $from = $messages->first()->from;

        $messageIds = $messages->pluck(['id']);

        broadcast(new messageSeen($from, $messageIds));

        foreach ($messages as $message) {
            $message->update([
                'status' => MessageStatuses::READ,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $roomId
     * @param User $user
     * @return MessageResource
     */
    public function store(Request $request, string $roomId, User $user)
    {
        $filename = null;
        if ($request->has('file')) {
            $filename = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('chat'), $filename);
        }

        $message = Message::query()
            ->create([
                'from' => auth()->id(),
                'to' => $user->id,
                'room_id' => $roomId,
                'message' => $request->input('message'),
                'image' => $filename,
                'status' => \App\MessageStatuses::UNREAD
            ]);

        broadcast(new messageSend($message));

        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MessageEditFormRequest $request, Message $message)
    {
        $attributes = $request->validated();

        $attributes['updated_at'] = now();

        $message->update($attributes);

        broadcast(new messageEdited($message));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function receive(Message $message)
    {
        $message->update([
            'status' => MessageStatuses::READ,
        ]);

        broadcast(new messageSeen($message->from, collect($message->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $id = $message->id;
        $userId = $message->to;

        try {
            $message->delete();

            broadcast(new MessageDeleted($id, $userId));
        } catch (\Exception $e) {
        }

        return response(200);
    }
}
