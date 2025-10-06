<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\FunctionalityWalkthrough\FunctionalityWalkthroughController;

/* =================================================
===============< Functionality Walkthrough Routes >==============
==================================================*/
Route::controller(FunctionalityWalkthroughController::class)->prefix('functionality_walkthrough')->name('functionality_walkthrough.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Functionality Walkthrough Everything');
    Route::get('/my', 'my')->name('my')->can('Functionality Walkthrough Read');
    Route::get('/create', 'create')->name('create')->can('Functionality Walkthrough Create');
    Route::post('/store', 'store')->name('store')->can('Functionality Walkthrough Create');

    Route::get('/show/{functionalityWalkthrough}', 'show')->name('show')->can('Functionality Walkthrough Read');
    Route::get('/edit/{functionalityWalkthrough}', 'edit')->name('edit')->can('Functionality Walkthrough Update');
    Route::post('/update/{functionalityWalkthrough}', 'update')->name('update')->can('Functionality Walkthrough Update');
    Route::get('/destroy/{functionalityWalkthrough}', 'destroy')->name('destroy')->can('Functionality Walkthrough Delete');
});
