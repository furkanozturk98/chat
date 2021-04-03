<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\ProfileSettingController;
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

    Route::get('friend-list',[ChatController::class,'getFriendList']);

    Route::post('friend-request',[FriendRequestController::class,'friendRequest']);
    Route::get('get-friend-requests',[FriendRequestController::class,'getFriendRequests']);

    Route::put('friend-request/approve/{friendRequest}',[FriendRequestController::class,'approve']);
    Route::put('friend-request/reject/{friendRequest}',[FriendRequestController::class,'reject']);
    Route::delete('friend-request/cancel/{friendRequest}',[FriendRequestController::class,'cancel']);

    Route::get('profile-setting',[ProfileSettingController::class,'index']);
    Route::post('profile-setting',[ProfileSettingController::class,'update']);

    Route::get('message/{roomId}',[MessageController::class,'index']);
    Route::post('message/send/{roomId}/to/{user}',[MessageController::class,'store']);
    Route::put('message/update/{message}',[MessageController::class,'update']);
    Route::delete('message/delete/{message}',[MessageController::class,'destroy']);

});
