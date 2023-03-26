<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatuses;
use App\Events\messageDeleted;
use App\Events\messageSeen;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageEditFormRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function __construct(public MessageService $messageService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $roomId
     *
     * @return AnonymousResourceCollection
     */
    public function index(string $roomId): AnonymousResourceCollection
    {
        $messages = $this->messageService->getMessagesByRoomId($roomId);

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param string  $roomId
     * @param User    $user
     *
     * @return MessageResource
     */
    public function store(Request $request, string $roomId, User $user): MessageResource
    {
        $message = $this->messageService->createMessageByRoomId($request, $roomId, $user);

        return new MessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MessageEditFormRequest $request
     * @param Message                $message
     *
     * @return MessageResource
     */
    public function update(MessageEditFormRequest $request, Message $message): MessageResource
    {
        $attributes = $request->validated();

        $this->messageService->updateMessage($attributes, $message);

        return new MessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Message $message
     *
     * @return MessageResource
     */
    public function receive(Message $message): MessageResource
    {
        $message->update([
            'status' => MessageStatuses::READ,
        ]);

        broadcast(new messageSeen($message->from, collect($message->id)));

        return new MessageResource($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     *
     * @return Response
     */
    public function destroy(Message $message): Response
    {
        $id     = $message->id;
        $userId = $message->to;

        try {
            $message->delete();

            broadcast(new MessageDeleted($id, $userId));
        } catch (Exception $e) {
            \Log::error('Error on deleting message', [
                'message' => $e->getMessage(),
                'id'      => $id,
                'user_id' => $userId,
            ]);
        }

        return \response()->noContent();
    }
}
