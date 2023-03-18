<?php

namespace App\Services;

use App\Enums\FriendRequestStatuses;
use App\Models\Friend;
use App\Models\FriendRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Str;

class FriendRequestService
{
    public function __construct(public UserService $userService)
    {

    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return FriendRequest::query()
            ->where('status', '=', FriendRequestStatuses::WAITING)
            ->where(function($query) {
                $query->where('from', auth()->id())
                    ->orWhere('to', auth()->id());
            })->get();
    }

    /**
     * @param Request $request
     *
     * @return FriendRequest
     */
    public function create(Request $request): FriendRequest
    {
        $user = $this->userService->findUserByEmail($request->input('email'));

        return FriendRequest::query()->create([
            'from'   => auth()->id(),
            'to'     => $user->id,
            'status' => FriendRequestStatuses::WAITING,
        ]);
    }

    /**
     * @param $friendRequest
     *
     * @return void
     */
    public function approve($friendRequest): void
    {
        $friendRequest->status = FriendRequestStatuses::APPROVED;
        $friendRequest->save();
        $room = Str::random(5);

        Friend::query()
            ->create([
                'user_id'   => $friendRequest->from,
                'friend_id' => $friendRequest->to,
                'room_id'   => $room,
            ]);

        Friend::query()
            ->create([
                'user_id'   => $friendRequest->to,
                'friend_id' => $friendRequest->from,
                'room_id'   => $room,
            ]);
    }
}
