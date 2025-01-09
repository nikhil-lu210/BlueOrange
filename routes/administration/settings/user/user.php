<?php

use App\Http\Controllers\Administration\Settings\User\UserController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< User Routes >==============
===============================================*/
Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/all', 'index')->name('index')->can('User Read');

            Route::get('/barcode', 'allBarcodes')->name('barcode.all')->can('User Create');
            Route::get('/barcode/download/all', 'downloadAllBarcodes')->name('barcode.all.download')->can('User Create');

            Route::get('/create', 'create')->name('create')->can('User Create');
            Route::post('/store', 'store')->name('store')->can('User Create');
            Route::get('/edit/{user}', 'edit')->name('edit')->can('User Update');
            Route::post('/update/{user}', 'update')->name('update')->can('User Update');
            Route::get('/destroy/{user}', 'destroy')->name('destroy')->can('User Delete');

            Route::get('/generate-qr-code/{user}', 'generateQrCode')->name('generate.qr.code')->can('User Create');
            Route::get('/generate-bar-code/{user}', 'generateBarCode')->name('generate.bar.code')->can('User Create');
            
            Route::get('/show/{user}/profile', 'showProfile')->name('show.profile')->can('User Read');
            Route::get('/show/{user}/attendance', 'showAttendance')->name('show.attendance')->can('User Read');

            Route::post('/shift/{shift}/update/{user}', 'updateShift')->name('shift.update')->can('User Update');

            // user_interaction
            include_once 'user_interaction/user_interaction.php';

            // leave_allowed
            include_once 'leave_allowed/leave_allowed.php';

            // salary
            include_once 'salary/salary.php';
        });