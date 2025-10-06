<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< api Routes >============
===============================================*/
// Route::prefix('api')
Route::prefix('')->group(function () {
    // Attendance
    include_once 'attendance/attendance.php';
});
