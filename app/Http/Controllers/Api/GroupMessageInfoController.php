<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupMessageInfoIndexRequest;
use App\Http\Resources\GroupMessageInfoResource;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
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
        /** @var GroupMessage $groupMessage */
        $groupMessage = $this->messageService->findById($request->input('message_id'));

        $groupMessageStatuses = GroupMessageStatus::query()
            ->where('group_id', $request->input('group_id'))
            ->where('message_id', $groupMessage->id)
            ->where('member_id', '!=', $groupMessage->sender)
            ->get();

        return GroupMessageInfoResource::collection($groupMessageStatuses);
    }
}
