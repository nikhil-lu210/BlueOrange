<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\LifeCycle\OverviewController;

/* ==============================================
===============< LifeCycle Routes >==============
===============================================*/
Route::controller(OverviewController::class)->prefix('lifecycle')->name('lifecycle.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('LifeCycle Everything');
    Route::get('/onboarding', 'onboarding')->name('onboarding')->can('LifeCycle Read');
    Route::get('/my', 'my')->name('my')->can('LifeCycle Read');
    Route::get('/create', 'create')->name('create')->can('LifeCycle Create');
    Route::post('/store', 'store')->name('store')->can('LifeCycle Create');
    
    Route::get('/show/{event}', 'show')->name('show')->can('LifeCycle Read');
    Route::get('/edit/{event}', 'edit')->name('edit')->can('LifeCycle Update');
    Route::post('/update/{event}', 'update')->name('update')->can('LifeCycle Update');
    Route::get('/destroy/{event}', 'destroy')->name('destroy')->can('LifeCycle Delete');
});
