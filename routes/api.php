<?php

use App\Http\Controllers\ChatController;
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

    Route::post('friend-request',[ChatController::class,'friendRequest']);
    Route::get('get-friend-requests',[ChatController::class,'getFriendRequests']);

    Route::put('friend-request/approve/{friendRequest}',[ChatController::class,'approve']);
    Route::put('friend-request/reject/{friendRequest}',[ChatController::class,'reject']);
    Route::delete('friend-request/cancel/{friendRequest}',[ChatController::class,'cancel']);

    Route::get('profile-setting',[ProfileSettingController::class,'index']);
    Route::post('profile-setting',[ProfileSettingController::class,'update']);


});
