<?php

use App\Http\Controllers\Auth\ImpersonateController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Impersonate Routes >==============
===============================================*/
Route::prefix('impersonate')->name('impersonate.')->group(function () {
    Route::controller(ImpersonateController::class)->group(function () {
        Route::get('/login/{user}', 'login')->name('login')->role(['Developer', 'Super Admin']);

        Route::get('/revert', 'revert')->name('revert');
    });
});
