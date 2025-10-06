<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Event\EventController;

/* ==============================================
===============< Event Routes >==============
===============================================*/
Route::controller(EventController::class)->prefix('event')->name('event.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Event Everything');
    Route::get('/my', 'my')->name('my')->can('Event Read');
    Route::get('/create', 'create')->name('create')->can('Event Create');
    Route::post('/store', 'store')->name('store')->can('Event Create');
    
    Route::get('/show/{event}', 'show')->name('show')->can('Event Read');
    Route::get('/edit/{event}', 'edit')->name('edit')->can('Event Update');
    Route::post('/update/{event}', 'update')->name('update')->can('Event Update');
    Route::get('/destroy/{event}', 'destroy')->name('destroy')->can('Event Delete');
    
    // Calendar routes
    Route::get('/calendar', 'calendar')->name('calendar')->can('Event Read');
    Route::post('/update-datetime/{eventId}', 'updateDateTime')->name('updateDateTime')->can('Event Update');
});
