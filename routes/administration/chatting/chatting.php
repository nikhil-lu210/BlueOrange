<?php

use App\Http\Controllers\Administration\Chatting\ChattingController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Chatting Routes >===============
===============================================*/
Route::controller(ChattingController::class)->prefix('chatting')->name('chatting.')->group(function () {
    Route::get('/one-to-one/', 'index')->name('index');
    Route::get('/one-to-one/{user}/{userid}', 'show')->name('show');

    // group_chatting
    include_once 'group_chatting.php';
});

Route::controller(ChattingController::class)->prefix('chatting')->name('chatting.')->group(function () {
    Route::get('/one-to-one/browser-unread-messages', 'fetchUnreadMessagesForBrowser')->name('browser.fetch_unread');
    Route::get('/one-to-one/read-browser-notification-message/{id}/{userid}', 'readBrowserNotification')->name('browser.read.message');
});
