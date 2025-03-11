<?php

use App\Http\Controllers\Administration\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Notification Routes >==============
===============================================*/
Route::controller(NotificationController::class)->prefix('notification')->name('notification.')->group(function () {
    Route::get('/all', 'index')->name('index');

    Route::get('/mark-as-read-and-redirect/{notification_id}', 'markAsReadAndRedirect')->name('mark_as_read_and_redirect');
    Route::get('/mark-as-read/{notification_id}', 'markAsRead')->name('mark_as_read');
    Route::get('/mark-all-as-read', 'markAllAsRead')->name('mark_all_as_read');

    Route::get('/delete/{notification_id}', 'destroy')->name('destroy');
    Route::get('/destroy/all', 'destroyAll')->name('destroy.all');

    Route::get('/get-unread-notifications-for-browser', 'getUnreadNotificationsForBrowser')->name('browser.get_unread');
    Route::get('/mark-as-read-notifications-for-browser/{notification_id}', 'markAsReadForBrowser')->name('browser.mark_as_read');
});
