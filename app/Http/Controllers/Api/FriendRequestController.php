<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FriendRequestFormRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\Services\FriendRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FriendRequestController extends Controller
{
    public function __construct(public FriendRequestService $friendRequestService)
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $data = $this->friendRequestService->get();

        return FriendRequestResource::collection($data);
    }

    /**
     * @param FriendRequestFormRequest $request
     *
     * @return FriendRequestResource|JsonResponse
     */
    public function store(FriendRequestFormRequest $request): FriendRequestResource|JsonResponse
    {
        $data = $this->friendRequestService->create($request);

        return new FriendRequestResource($data);
    }

    /**
     * @param FriendRequest $friendRequest
     *
     * @return JsonResponse
     */
    public function approve(FriendRequest $friendRequest): JsonResponse
    {
        $this->friendRequestService->approve($friendRequest);

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * @param FriendRequest $friendRequest
     *
     * @return Response
     */
    public function reject(FriendRequest $friendRequest): Response
    {
        $friendRequest->delete();

        return response()->noContent();
    }

    /**
     * @param FriendRequest $friendRequest
     *
     * @return Response
     */
    public function cancel(FriendRequest $friendRequest): Response
    {
        $friendRequest->delete();

        return response()->noContent();
    }
}
