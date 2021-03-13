<?php

namespace App\Http\Controllers;

use App\Friend;
use App\FriendRequestStatuses;
use App\Http\Requests\AddFriendFormRequest;
use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\FriendResource;
use App\Models\FriendRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class ChatController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function getfriendList(): AnonymousResourceCollection
    {

        $friends = Friend::query()
            ->where('user_id',auth()->id())
            ->with('friend')
            ->get();

        return FriendResource::collection($friends);
    }

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

        $request = Friend::query()
            ->where('user_id',auth()->id())
            ->where('friend_id', $user->id)
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
            'room' => $room
        ],[
            'user_id' => $friendRequest->to,
            'friend_id' => $friendRequest->from,
            'room' => $room
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
