<?php

use App\Events\groupMessageSend;
use App\Events\messageSend;
use App\Http\Controllers\FacebookLoginController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TwitterLoginController;
use App\Models\GroupMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[MainController::class,'index'])->middleware('auth')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test',function (){
    return view('broadcastTest');
});

Route::get('/publish',function (){
    /*event(new MyEvent('hello world'));*/

    $roomId = \App\Models\Message::where('from',6)->where('to',1)->first()->room_id;

    $message = \App\Models\Message::create([
        'from' => 6,
        'to'   => 1,
        'message' => 'selam aaa',
        'room_id' => $roomId,
        'status' => \App\Enums\MessageStatuses::UNREAD
    ]);
    broadcast(new messageSend($message));
});

Route::get('/groupMessage',function (){
    /*event(new MyEvent('hello world'));*/

    $message = GroupMessage::create([
        'group_id' => 3,
        'sender'   => 6,
        'content' => 'aaaaaa',
    ]);
    broadcast(new groupMessageSend($message));
});

Route::get('/login/google', [GoogleLoginController::class, 'redirectToProvider'])->name('google-login-redirect');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleProviderCallback'])->name('google-login-callback');

Route::get('/login/facebook', [FacebookLoginController::class, 'redirectToProvider']);
Route::get('/login/facebook/callback', [FacebookLoginController::class, 'handleProviderCallback']);

Route::get('/login/twitter', [TwitterLoginController::class, 'redirectToProvider']);
Route::get('/login/twitter/callback', [TwitterLoginController::class, 'handleProviderCallback']);
