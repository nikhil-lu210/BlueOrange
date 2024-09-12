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
});