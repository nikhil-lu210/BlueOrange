<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Dashboard\DashboardController;


/* ==============================================
===============< Dashboard Routes >==============
===============================================*/
Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', 'index')->name('index');
});

Route::get('/test/qr/{qr}', [DashboardController::class, 'testqr']);