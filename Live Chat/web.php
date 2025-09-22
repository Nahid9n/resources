<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLiveChatController;
use App\Http\Controllers\LiveChatController;


// Live Chat
Route::post('/chat/start', [LiveChatController::class,'start'])->name('guest.start');
Route::get('/chat/{sid}', [LiveChatController::class,'box'])->name('guest.chat');
Route::get('/guest/check/{sid}', [LiveChatController::class, 'check'])->name('guest.check');
Route::get('/chat/{sid}/messages', [LiveChatController::class,'fetch'])->name('guest.fetch');
Route::post('/chat/send',        [LiveChatController::class,'send'])->name('guest.send');
Route::post('/chat/{sid}/typing',        [LiveChatController::class,'typing'])->name('guest.typing');
Route::get('/chat/{sid}/typing-status',  [LiveChatController::class,'typingStatus'])->name('guest.typing-status');
Route::post('/chat/s', [LiveChatController::class,'end'])->name('guest.end');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {

    // Live Chat Admin
    Route::get('/chat', [AdminLiveChatController::class,'index'])->name('chat.index');
    Route::get('/chat/list', [AdminLiveChatController::class,'chatList'])->name('chat.list');
    Route::get('/chat/{sid}/messages', [AdminLiveChatController::class,'fetch'])->name('chat.fetch');
    Route::get('/chat/seen-status', [AdminLiveChatController::class,'seenStatus'])->name('chat.seen.status');
    Route::post('/chat/send',          [AdminLiveChatController::class,'send'])->name('chat.send');
    Route::post('/chat/close', [AdminLiveChatController::class,'close'])->name('chat.close');

    //admin logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
