<?php


namespace App\Repositories;


use App\Enums\FriendRequestStatuses;
use App\Http\Resources\FriendRequestResource;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class FriendRequestRepository
{
    public function index(): Collection
    {
        return FriendRequest::query()
            ->where('status', '=',FriendRequestStatuses::WAITING)
            ->where(function ($query) {
                $query->where('from', auth()->id())
                    ->orWhere('to', auth()->id());
            })->get();
    }

    public function store($request): FriendRequestResource|JsonResponse
    {
        $user = $this->getUser($request);

        if (auth()->user()->email === $request->input('email')) {
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => ['email' => ['You cannot send friend request to yourself']]
            ], 422);
        }

        if (!$this->checkUserIsValid($user)) {
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => ['email' => ['The email is not exist.Please enter an exist email.']]
            ], 422);
        }

        if ($this->checkFriendIsExist($user)) {
            return response()->json([
                'message' => "The user of this email is already your friend.",
                'errors' => ['email' => ['The user of this email is already your friend']]
            ], 422);
        }

        if (!$this->checkFriendRequestIsExist($user)) {
            $data = FriendRequest::query()->create([
                'from' => auth()->id(),
                'to' => $user->id,
                'status' => FriendRequestStatuses::WAITING
            ]);

            return new FriendRequestResource($data);
        }
        return response()->json([
            'message' => "Already a friendship request send to this email.",
            'errors' => ['email' => ['Already a friendship request send to this email.']]
        ], 422);
    }

    public function getUser($request): User|null
    {
        return User::query()
            ->where('email', $request->input('email'))
            ->first();
    }

    public function checkUserIsValid($user): bool
    {
        if (isset($user)) {
            return true;
        }

        return false;
    }

    public function checkFriendIsExist($user): bool
    {
        $friend = Friend::query()
            ->where('user_id', auth()->user()->id)
            ->where('friend_id', $user->id)
            ->first();

        if (isset($friend)) {
            return true;
        }

        return false;
    }

    public function checkFriendRequestIsExist($user): bool
    {
        $data = FriendRequest::query()
            ->where('from', auth()->id())
            ->where('to', $user->id)
            ->first();

        if (isset($data)) {
            return true;
        }

        return false;
    }

    public function approve($friendRequest): Friend|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        $friendRequest->status = FriendRequestStatuses::APPROVED;
        $friendRequest->save();
        $room = \Str::random(5);

        $first = Friend::query()
            ->create([
                'user_id' => $friendRequest->from,
                'friend_id' => $friendRequest->to,
                'room_id' => $room
            ]);

        $second = Friend::query()
            ->create([
                'user_id' => $friendRequest->to,
                'friend_id' => $friendRequest->from,
                'room_id' => $room
            ]);


        return $second;
    }
}
