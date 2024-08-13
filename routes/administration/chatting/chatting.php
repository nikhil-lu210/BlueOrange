<?php

use App\Http\Controllers\Administration\Chatting\ChattingController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Chatting Routes >==============
===============================================*/
Route::controller(ChattingController::class)->prefix('chatting')->name('chatting.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{user}/{userid}', 'show')->name('show');
});