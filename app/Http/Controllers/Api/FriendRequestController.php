<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFriendFormRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\Repositories\FriendRequestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class FriendRequestController extends Controller
{

    private FriendRequestRepository $friendRequestRepository;

    function __construct(FriendRequestRepository $friendRequestRepository) {
        $this->friendRequestRepository = $friendRequestRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $data = $this->friendRequestRepository->index();

        return FriendRequestResource::collection($data);
    }

    /**
     * @param AddFriendFormRequest $request
     *
     * @return FriendRequestResource|JsonResponse
     */
    public function store(AddFriendFormRequest $request): FriendRequestResource|JsonResponse
    {
        return $this->friendRequestRepository->store($request);

    }

    /**
     * @param FriendRequest $friendRequest
     */
    public function approve(FriendRequest $friendRequest): bool
    {

        return $this->friendRequestRepository->approve($friendRequest);
    }

    /**
     * @param FriendRequest $friendRequest
     */
    public function reject(FriendRequest $friendRequest): JsonResponse
    {
        try{
            $friendRequest->delete();
        }
        catch(Throwable $e){}

        return response()->json(204);
    }

    /**
     * @param FriendRequest $friendRequest
     */
    public function cancel(FriendRequest $friendRequest): JsonResponse
    {
        try{
            $friendRequest->delete();
        }
        catch(Throwable $e){}

        return response()->json(204);
    }

}
