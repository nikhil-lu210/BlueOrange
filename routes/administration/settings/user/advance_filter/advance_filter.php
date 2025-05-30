<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Settings\User\UserAdvanceFiterController;


/* ====================================================
===============< User Advance Filter Routes >==============
=====================================================*/
Route::controller(UserAdvanceFiterController::class)
        ->prefix('advance_filter')
        ->name('advance_filter.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('User Read');
        });
