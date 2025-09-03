<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Leave\LeaveHistoryController;

/* ==============================================
===============< Leave Routes >==================
===============================================*/
Route::prefix('leave')
    ->name('leave.')
    ->group(function () {
        Route::controller(LeaveHistoryController::class)
            ->prefix('history')
            ->name('history.')
            ->group(function () {
                Route::get('/all', 'index')->name('index')->can('Leave History Update');
                Route::get('/my', 'my')->name('my')->can('Leave History Read');
                Route::get('/create', 'create')->name('create')->can('Leave History Create');
                Route::get('/show/{leaveHistory}', 'show')->name('show')->can('Leave History Read');

                Route::put('/approve/{leaveHistory}', 'approve')->name('approve')->can('Leave History Update')->middleware('throttle:10,1'); // 10 approvals per minute
                Route::put('/reject/{leaveHistory}', 'reject')->name('reject')->can('Leave History Update')->middleware('throttle:10,1'); // 10 rejections per minute
                Route::put('/cancel/{leaveHistory}', 'cancel')->name('cancel')->can('Leave History Update')->middleware('throttle:3,1'); // 3 cancellations per minute

                Route::post('/store', 'store')->name('store')->can('Leave History Create')->middleware('throttle:5,1'); // 5 requests per minute

                Route::get('/export', 'export')->name('export')->can('Leave History Read');
            });
    });
