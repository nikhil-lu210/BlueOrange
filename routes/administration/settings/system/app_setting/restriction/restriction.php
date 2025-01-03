<?php

use App\Http\Controllers\Administration\Settings\System\AppSetting\RestrictionController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Restriction Routes >==============
===============================================*/
Route::controller(RestrictionController::class)
        ->prefix('restrictions')
        ->name('restriction.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('App Setting Update');
            Route::put('/update', 'update')->name('update')->can('App Setting Update');
        });