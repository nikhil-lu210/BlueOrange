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
            Route::get('/edit/{user:userid}', 'edit')->name('edit')->can('User Update');
            Route::post('/update/{user:userid}', 'update')->name('update')->can('User Update');
            Route::get('/destroy/{user:userid}', 'destroy')->name('destroy')->can('User Delete');

            Route::get('/generate-qr-code/{user}', 'generateQrCode')->name('generate.qr.Code')->can('User Create');
            
            Route::get('/show/{user:userid}/profile', 'showProfile')->name('show.profile')->can('User Read');
            Route::get('/show/{user:userid}/attendance', 'showAttendance')->name('show.attendance')->can('User Read');
            Route::get('/show/{user:userid}/break', 'showBreak')->name('show.break')->can('User Read');
            Route::get('/show/{user:userid}/task', 'showTask')->name('show.task')->can('User Read');

            Route::post('/shift/{shift}/update/{user}', 'updateShift')->name('shift.update')->can('User Update');


            // salary
            include_once 'salary/salary.php';
        });