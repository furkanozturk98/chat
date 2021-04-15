<?php

namespace App\Http\Controllers\Api;

use App\GroupInviteStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\sendGroupInviteFormRequest;
use App\Http\Resources\GroupInviteResource;
use App\Models\GroupInvite;
use App\Models\GroupMember;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class GroupInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $friendRequests = GroupInvite::query()
            ->where('from', auth()->id())
            ->orWhere('to', auth()->id())
            ->where('status', 0)
            ->get();

        return GroupInviteResource::collection($friendRequests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param sendGroupInviteFormRequest $request
     * @return Builder|Model|JsonResponse
     */
    public function store(sendGroupInviteFormRequest $request)
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

        $request = GroupInvite::query()
            ->where('from',auth()->id())
            ->where('to', $user->id)
            ->first();

        if(!isset($request)){
            return GroupInvite::query()->create([
                'from' => auth()->id(),
                'to' => $user->id,
                'status' => GroupInviteStatuses::WAITING
            ]);
        }

        return response()->json([
            'message' => "Already a group invite send to this email.",
            'errors' => ['email' => ['Already a group invite send to this email.'] ]
        ],422);
    }

    public function approve(GroupInviteFormRequest $groupInvite){

        $groupInvite->status = GroupInviteStatuses::APPROVED;

        $groupInvite->save();

        GroupMember::query()->insert([
                'group_id' => $groupInvite->group_id,
                'member_id' => $groupInvite->to,
            ]);
    }

    public function reject(GroupInvite $groupInvite){
        try{
            $groupInvite->delete();
        }
        catch(Throwable $e){}
    }

    public function cancel(GroupInvite $groupInvite){
        try{
            $groupInvite->delete();
        }
        catch(Throwable $e){}
    }
}
