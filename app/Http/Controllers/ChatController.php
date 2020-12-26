<?php

namespace App\Http\Controllers;

use App\Friend;
use App\FriendRequestStatuses;
use App\Http\Requests\AddFriendFormRequest;
use App\Http\Resources\FriendResource;
use App\User;
use Throwable;

class ChatController extends Controller
{
    public function getfriendList(){

        $friends = Friend::query()->where('user_id',auth()->id())->with('friend')->get();
        return FriendResource::collection($friends);
    }

    public function friendRequest(AddFriendFormRequest $request)
    {
        $user = User::query()->where('email',$request->email)->first();

        if(!isset($user)){
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => ['email' => ['The email is not exist.Please enter an exist email.'] ]
            ],422);
        }

        $request = Friend::query()->where('user_id',auth()->id())
            ->where('friend_id', $user->id)->first();

        if(!isset($request)){
            /** @var User $user */
            return Friend::query()->create([
                'user_id' => auth()->id(),
                'friend_id' => $user->id,
                'status' => FriendRequestStatuses::WAITING
            ]);
        }

        return response()->json([
            'message' => "Already a friendship request send to this email.",
            'errors' => ['email' => ['Already a friendship request send to this email.'] ]
        ],422);

    }

    public function getFriendRequests()
    {
        $friendRequests = Friend::query()
            ->where('user_id', auth()->id())
            ->orWhere('friend_id', auth()->id())
            ->where('status', 1)
            ->get();

        return FriendResource::collection($friendRequests);

    }

    public function approve(Friend $friend){
        $friend->status = FriendRequestStatuses::APPROVED;
        $friend->save();
    }

    public function reject(Friend $friend){
        try{
            $friend->delete();
        }
        catch(Throwable $e){}
    }

    public function cancel(Friend $friend){
        try{
            $friend->delete();
        }
        catch(Throwable $e){}
    }

}
