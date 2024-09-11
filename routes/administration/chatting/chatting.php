<?php

use App\Http\Controllers\Administration\Chatting\ChattingController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Chatting Routes >===============
===============================================*/
Route::controller(ChattingController::class)->prefix('chatting')->name('chatting.')->group(function () {
    Route::get('/private/', 'index')->name('index');
    Route::get('/private/{user}/{userid}', 'show')->name('show');

    // group_chatting
    include_once 'group_chatting.php';
});