<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Administration Routes >============
===============================================*/
// Route::prefix('administration')
Route::prefix('')
        ->name('administration.')
        ->group(function () {
            // file_media
            include_once 'file_media/file_media.php';

            // notification
            include_once 'notification/notification.php';

            // Dashboard
            include_once 'dashboard/dashboard.php';

            // chatting
            include_once 'chatting/chatting.php';

            // Attendance
            include_once 'attendance/attendance.php';

            // announcement
            include_once 'announcement/announcement.php';

            // task
            include_once 'task/task.php';
            
            // Profile
            include_once 'profile/profile.php';

            // settings
            include_once 'settings/settings.php';

            // shortcut
            include_once 'shortcut/shortcut.php';
        });