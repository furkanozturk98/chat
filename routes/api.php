<?php


use App\Http\Controllers\api\FriendController;
use App\Http\Controllers\Api\FriendRequestController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupInviteController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\Api\GroupMessageController;
use App\Http\Controllers\Api\GroupMessageInfoController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
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

Route::middleware('auth:api')->group(function() {
    Route::group(['prefix' => 'friend-requests'], function() {
        Route::get('/', [FriendRequestController::class, 'index']);
        Route::post('/', [FriendRequestController::class, 'store']);
        Route::put('{friendRequest}/approve', [FriendRequestController::class, 'approve']);
        Route::put('{friendRequest}/reject', [FriendRequestController::class, 'reject']);
        Route::delete('{friendRequest}/cancel', [FriendRequestController::class, 'cancel']);
    });

    Route::group(['prefix' => 'friends'], function() {
        Route::get('/', [FriendController::class, 'index']);
        Route::put('{user}/block', [FriendController::class, 'block']);
        Route::put('{user}/unblock', [FriendController::class, 'unblock']);
    });

    Route::group(['prefix' => 'groups'], function() {
        Route::get('/', [GroupController::class, 'index']);
        Route::post('/', [GroupController::class, 'store']);
    });

    Route::group(['prefix' => 'group-messages'], function() {
        Route::get('/', [GroupMessageController::class, 'index']);
        Route::get('info', [GroupMessageInfoController::class, 'index']);
        Route::post('/', [GroupMessageController::class, 'store']);
        Route::put('{groupMessage}', [GroupMessageController::class, 'update']);
        Route::delete('{groupMessage}', [GroupMessageController::class, 'destroy']);
        Route::put('{groupMessage}/receive', [GroupMessageController::class, 'receive']);
    });

    Route::group(['prefix' => 'group-invites'], function() {
        Route::get('/', [GroupInviteController::class, 'index']);
        Route::post('/', [GroupInviteController::class, 'store']);
        Route::put('{groupInvite}/approve', [GroupInviteController::class, 'approve']);
        Route::put('{groupInvite}/reject', [GroupInviteController::class, 'reject']);
        Route::put('{groupInvite}/cancel', [GroupInviteController::class, 'cancel']);
    });

    Route::group(['prefix' => 'group-members'], function() {
        Route::get('/', [GroupMemberController::class, 'index']);
        Route::put('{groupMember}/make-admin', [GroupMemberController::class, 'makeAdmin']);
        Route::put('{groupMember}/dismiss', [GroupMemberController::class, 'dismiss']);
        Route::delete('{groupMember}/remove', [GroupMemberController::class, 'destroy']);
    });

    Route::group(['prefix' => 'profile-settings'], function() {
        Route::get('/', [ProfileController::class, 'index']);
        Route::post('/', [ProfileController::class, 'update']);
    });

    Route::group(['prefix' => 'messages'], function() {
        Route::get('/', [MessageController::class, 'index']);
        Route::post('/', [MessageController::class, 'store']);
        Route::put('{message}/receive', [MessageController::class, 'receive']);
        Route::put('{message}', [MessageController::class, 'update']);
        Route::delete('{message}', [MessageController::class, 'destroy']);
    });
});
