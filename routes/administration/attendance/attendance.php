<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Attendance\AttendanceController;


/* ==============================================
===============< Attendance Routes >==============
===============================================*/
Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/all', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::post('/clockin', 'clockIn')->name('clockin');
    Route::post('/clockout', 'clockOut')->name('clockout');
});