<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMessageInfoIndexRequest;
use App\Http\Resources\GroupMessageInfoResource;
use App\Models\GroupMessageStatus;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMessageInfoController extends Controller
{
    public function __construct(public MessageService $messageService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param GroupMessageInfoIndexRequest $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(GroupMessageInfoIndexRequest $request): AnonymousResourceCollection
    {
        $messageId = $request->input('message_id');

        abort_if(empty($messageId), 404);

        /** @var Message $message */
        $message = $this->messageService->findById($messageId);

        $groupMessageStatuses = GroupMessageStatus::query()
            ->where('group_id', $request->input('group_id'))
            ->where('message_id', $message->id)
            ->where('member_id', '!=', $message->from)
            ->get();

        return GroupMessageInfoResource::collection($groupMessageStatuses);
    }
}
