<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware(['authentication'])->group(function () {
    Route::get('/home',[ChatController::class, 'index']);
    Route::get('/chat',[ChatController::class,'chat']);
    Route::post('/send-message',[ChatController::class,'sendMessage']);

    Route::get('/chat-private/{idUser}',[ChatController::class,'chatPrivate']);
    Route::post('/chat-private/search',[ChatController::class,'search']);
    
    Route::post('/message-private',[ChatController::class,'messagePrivate']); 
    Route::post('/create-group',[ChatController::class,'createGroup']);
    Route::get('/chat-group/{idgroup}',[ChatController::class,'chatGroup']);
    Route::post('/chat-group/search',[ChatController::class,'search']);
    Route::post('/user-inactive',[ChatController::class,'userInactive']);
    Route::post('/send-message-group',[ChatController::class,'sendMessageGroup']);
    Route::post('/search',[ChatController::class,'search']);
    
    
});