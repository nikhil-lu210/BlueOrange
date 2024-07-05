<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Administration Routes >============
===============================================*/
// Route::prefix('administration')
Route::prefix('')
        ->name('administration.')
        ->group(function () {
            // notification
            include_once 'notification/notification.php';

            // Attendance
            include_once 'attendance/attendance.php';

            // announcement
            include_once 'announcement/announcement.php';

            // Dashboard
            include_once 'dashboard/dashboard.php';
            
            // Profile
            include_once 'profile/profile.php';

            // settings
            include_once 'settings/settings.php';
        });