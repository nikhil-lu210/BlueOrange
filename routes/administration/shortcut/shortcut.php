<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Shortcut\ShorcutController;

/* ==============================================
===============< Shortcut Routes >==============
===============================================*/
Route::controller(ShorcutController::class)->prefix('shortcut')->name('shortcut.')->group(function () {
    Route::get('/all', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{shortcut}', 'edit')->name('edit');
    Route::post('/update/{shortcut}', 'update')->name('update');
    Route::get('/destroy/{shortcut}', 'destroy')->name('destroy');
});