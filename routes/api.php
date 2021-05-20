<?php


use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FriendRequestController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupInviteController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\Api\GroupMessageController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('friend-list', [ChatController::class,'index']);

    Route::get('get-friend-requests', [FriendRequestController::class,'index']);
    Route::post('friend-request', [FriendRequestController::class,'store']);
    Route::put('friend-request/approve/{friendRequest}', [FriendRequestController::class,'approve']);
    Route::put('friend-request/reject/{friendRequest}', [FriendRequestController::class,'reject']);
    Route::delete('friend-request/cancel/{friendRequest}', [FriendRequestController::class,'cancel']);

    Route::get('get-groups', [GroupController::class,'index']);
    Route::post('group', [GroupController::class,'store']);
    Route::put('group', [GroupController::class,'update']);

    Route::get('group-messages/{group}', [GroupMessageController::class,'index']);
    Route::post('group-message', [GroupMessageController::class,'store']);

    Route::get('get-group-invites', [GroupInviteController::class,'index']);
    Route::post('group-invite/{group}', [GroupInviteController::class,'store']);
    Route::put('group-invite/approve/{groupInvite}', [GroupInviteController::class,'approve']);
    Route::put('group-invite/reject/{groupInvite}', [GroupInviteController::class,'reject']);
    Route::delete('group-invite/cancel/{groupInvite}', [GroupInviteController::class,'cancel']);

    Route::get('group-member/{group}', [GroupMemberController::class,'show']);
    Route::post('group-member/add-friend/{groupMember}', [GroupMemberController::class,'store']);
    Route::put('group-member/make-admin/{groupMember}', [GroupMemberController::class,'update']);
    Route::put('group-member/dismiss-as-admin/{groupMember}', [GroupMemberController::class,'dismiss']);
    Route::delete('group-member/remove/{groupMember}', [GroupMemberController::class,'destroy']);


    Route::get('profile-setting', [ProfileSettingController::class,'index']);
    Route::post('profile-setting', [ProfileSettingController::class,'update']);

    Route::get('message/{roomId}', [MessageController::class,'index']);
    Route::post('message/send/{roomId}/to/{user}', [MessageController::class,'store']);
    Route::put('message/update/{message}', [MessageController::class,'update']);
    Route::delete('message/delete/{message}', [MessageController::class,'destroy']);
});
