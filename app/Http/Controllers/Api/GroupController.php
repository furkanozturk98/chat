<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateFormRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return GroupResource
     */
    public function index()
    {
        $groupIds = GroupMember::query()
            ->where('member_id', auth()->id())
            ->get()
            ->pluck(['id']);

        $groups = Group::query()
            ->whereIn('id', $groupIds)
            ->get();

        return new GroupResource($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupCreateFormRequest $request
     * @return GroupResource
     */
    public function store(GroupCreateFormRequest $request)
    {
        $attributes = [
            'name' => $request->input('name'),
            'created_by' => $request->user()->id
        ];

        $group = Group::query()
            ->create($attributes);

        return new GroupResource($group);
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
