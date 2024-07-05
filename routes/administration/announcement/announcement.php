<?php

use App\Http\Controllers\Administration\Announcement\AnnouncementController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Announcement Routes >==============
===============================================*/
Route::controller(AnnouncementController::class)->prefix('announcement')->name('announcement.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Announcement Read');
    Route::get('/my', 'my')->name('my')->can('Announcement Read');
    Route::get('/create', 'create')->name('create')->can('Announcement Create');
    Route::post('/store', 'store')->name('store')->can('Announcement Create');
    
    Route::get('/show/{announcement}', 'show')->name('show')->can('Announcement Read');
    Route::get('/edit/{announcement}', 'edit')->name('edit')->can('Announcement Update');
    Route::post('/update/{announcement}', 'update')->name('update')->can('Announcement Update');
    Route::get('/destroy/{announcement}', 'destroy')->name('destroy')->can('Announcement Delete');
});