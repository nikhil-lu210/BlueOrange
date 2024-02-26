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
            Route::get('/create', 'create')->name('create')->can('User Create');
            Route::post('/store', 'store')->name('store')->can('User Create');
            Route::get('/edit/{user}', 'edit')->name('edit')->can('User Update');
            Route::post('/update/{user}', 'update')->name('update')->can('User Update');
            Route::get('/destroy/{user}', 'destroy')->name('destroy')->can('User Delete');

            Route::get('/generate-qr-code/{user}', 'generateQrCode')->name('generate.qr.Code')->can('User Create');
            
            Route::get('/show/profile/{user}', 'showProfile')->name('show.profile')->can('User Read');
            Route::get('/show/attendance/{user}', 'showAttendance')->name('show.attendance')->can('User Read');
            Route::get('/show/break/{user}', 'showBreak')->name('show.break')->can('User Read');

            Route::post('/shift/{shift}/update/{user}', 'updateShift')->name('shift.update')->can('User Update');


            // salary
            include_once 'salary/salary.php';
        });