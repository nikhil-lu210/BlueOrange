<?php

use App\Http\Controllers\Administration\Logs\LoginLogout\LoginLogoutHistoryController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Login Logout History Routes >==============
===============================================*/
Route::controller(LoginLogoutHistoryController::class)->prefix('login_logout_history')->name('login_logout_history.')->group(function () {
    Route::get('/', 'index')->name('index')->can('Logs Read');
});