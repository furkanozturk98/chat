<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Friend;
use App\FriendRequestStatuses;
use App\Http\Requests\AddFriendFormRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class FriendRequestController extends Controller
{
    /**
     * @param AddFriendFormRequest $request
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|JsonResponse
     */
    public function friendRequest(AddFriendFormRequest $request)
    {
        $user = User::query()
            ->where('email',$request->input('email'))
            ->first();

        if(!isset($user)){
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => ['email' => ['The email is not exist.Please enter an exist email.'] ]
            ],422);
        }

        $request = FriendRequest::query()
            ->where('from',auth()->id())
            ->where('to', $user->id)
            ->first();

        if(!isset($request)){
            /** @var User $user */
            return FriendRequest::query()->create([
                'from' => auth()->id(),
                'to' => $user->id,
                'status' => FriendRequestStatuses::WAITING
            ]);
        }

        return response()->json([
            'message' => "Already a friendship request send to this email.",
            'errors' => ['email' => ['Already a friendship request send to this email.'] ]
        ],422);

    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getFriendRequests(): AnonymousResourceCollection
    {
        $friendRequests = FriendRequest::query()
            ->where('from', auth()->id())
            ->orWhere('to', auth()->id())
            ->where('status', 0)
            ->get();

        return FriendRequestResource::collection($friendRequests);

    }

    public function approve(FriendRequest $friendRequest){

        /** @var FriendRequest $friendRequest */
        $friendRequest->status = FriendRequestStatuses::APPROVED;

        $friendRequest->save();

        $room = \Str::random(5);

        Friend::query()->insert([
            [
                'user_id' => $friendRequest->from,
                'friend_id' => $friendRequest->to,
                'room_id' => $room
            ],[
                'user_id' => $friendRequest->to,
                'friend_id' => $friendRequest->from,
                'room_id' => $room
            ]]);
    }

    public function reject(FriendRequest $friendRequest){
        try{
            $friendRequest->delete();
        }
        catch(Throwable $e){}
    }

    public function cancel(FriendRequest $friendRequest){
        try{
            $friendRequest->delete();
        }
        catch(Throwable $e){}
    }

}
