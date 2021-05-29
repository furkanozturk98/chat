<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMessageInfoResource;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\GroupMessageStatus;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMessageInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     *
     * @param GroupMessage $groupMessage
     *
     * @return AnonymousResourceCollection
     */
    public function index(Group $group, GroupMessage $groupMessage): AnonymousResourceCollection
    {
        $groupMessageStatuses = GroupMessageStatus::query()
            ->where('group_id', $group->id)
            ->where('message_id', $groupMessage->id)
            ->where('member_id', '!=', $groupMessage->sender)
            ->get();

        return GroupMessageInfoResource::collection($groupMessageStatuses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
