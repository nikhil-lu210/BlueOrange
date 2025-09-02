<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Administration Routes >============
===============================================*/
// Route::prefix('administration')
Route::prefix('')
        ->name('administration.')
        ->middleware(['localization', 'unrestricted.users', 'restrict.devices', 'restrict.ip'])
        ->group(function () {
            // logs
            include_once 'logs/logs.php';

            // certificate
            include_once 'certificate/certificate.php';

            // localization
            include_once 'localization/localization.php';

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

            // Leave
            include_once 'leave/leave.php';

            // Penalty
            include_once 'penalty/penalty.php';

            // Daily Break
            include_once 'daily_break/daily_break.php';

            // announcement
            include_once 'announcement/announcement.php';

            // task
            include_once 'task/task.php';

            // daily_work_update
            include_once 'daily_work_update/daily_work_update.php';

            // Profile
            include_once 'profile/profile.php';

            // settings
            include_once 'settings/settings.php';

            // accounts
            include_once 'accounts/accounts.php';

            // shortcut
            include_once 'shortcut/shortcut.php';

            // vault
            include_once 'vault/vault.php';

            // IT Ticket
            include_once 'ticket/it_ticket.php';

            // booking
            include_once 'booking/booking.php';

            // quiz
            include_once 'quiz/quiz.php';

            // recognition
            include_once 'recognition/recognition.php';

            // inventory
            include_once 'inventory/inventory.php';

            // learning_hub
            include_once 'learning_hub/learning_hub.php';
        });
