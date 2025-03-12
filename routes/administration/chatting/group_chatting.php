<?php

use App\Http\Controllers\Administration\Chatting\GroupChattingController;
use Illuminate\Support\Facades\Route;


/* ===================================================
===============< Group Chatting Routes >==============
====================================================*/
Route::controller(GroupChattingController::class)->prefix('group')->name('group.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{group}/{groupid}', 'show')->name('show');

    Route::post('/store', 'store')->name('store');
    Route::post('/store/users/{group}/{groupid}', 'addUsers')->name('store.users');
    Route::get('/remove/user/{group}/{groupid}/{user}', 'removeUser')->name('remove.user');
    Route::get('/{group}/{groupid}/destroy', 'destroy')->name('destroy');

    Route::get('/browser-unread-messages', 'fetchUnreadMessagesForBrowser')->name('browser.fetch_unread');
});
