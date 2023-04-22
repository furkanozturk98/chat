<?php

namespace App\Http\Controllers\Api;

use App\Events\userBlocked;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Models\User;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FriendController extends Controller
{
    public function __construct(public FriendService $friendService)
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $data = $this->friendService->get();

        return FriendResource::collection($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function block(User $user): JsonResponse
    {
        $status = $this->friendService->block($user);

        if ($status) {
            broadcast(new UserBlocked($user, auth()->id()));
        }

        return response()->json([
            'data' => [
                'status' => $status,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function unblock(User $user): JsonResponse
    {
        $status = $this->friendService->unblock($user);

        if ($status) {
            broadcast(new UserBlocked($user, null));
        }

        return response()->json([
            'data' => [
                'status' => $status,
            ],
        ]);
    }
}
