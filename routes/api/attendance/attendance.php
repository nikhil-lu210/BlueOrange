<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfflineAttendanceController;

// Offline Attendance API Routes
Route::controller(OfflineAttendanceController::class)->prefix('offline-attendance')->group(function () {
    // Status endpoint for connection testing
    Route::get('/status', 'status');

    // Authorize user for sensitive operations
    Route::post('/authorize', 'authorizeUser');

    // Get user data by userid for offline sync
    Route::get('/user/{userid}', 'getUserByUserid');

    // Check user attendance status on server
    Route::get('/user/{userid}/status', 'checkUserAttendanceStatus');

    // Sync offline attendance data
    Route::post('/sync', 'syncAttendances');

    // Get all users for offline sync (for initial data download)
    Route::get('/users', 'getAllUsers');
});
