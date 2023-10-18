<?php

use App\Http\Controllers\Administration\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Notification Routes >==============
===============================================*/
Route::controller(NotificationController::class)->prefix('notification')->name('notification.')->group(function () {
    Route::get('/mark-as-read/{notification_id}', 'markAsReadAndRedirect')->name('mark_as_read');
    Route::get('/mark-all-as-read', 'markAllAsRead')->name('mark_all_as_read');
});