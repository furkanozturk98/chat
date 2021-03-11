<?php

use App\Events\MyEvent;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MainController;
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
    event(new MyEvent('hello world'));
});

Route::get('/login/google', [LoginController::class, 'redirectToProvider'])->name('google-login-redirect');
Route::get('/login/google/callback', [LoginController::class, 'handleProviderCallback'])->name('google-login-callback');

Route::get('/login/facebook', [FacebookLoginController::class, 'redirectToProvider']);
Route::get('/login/facebook/callback', [FacebookLoginController::class, 'handleProviderCallback']);

Route::get('/login/twitter', [TwitterLoginController::class, 'redirectToProvider']);
Route::get('/login/twitter/callback', [TwitterLoginController::class, 'handleProviderCallback']);
