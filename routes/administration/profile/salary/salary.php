<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Profile\Salary\SalaryController;


/* ==============================================
===============< Salary Routes >==============
===============================================*/
Route::controller(SalaryController::class)
        ->prefix('salary')
        ->name('salary.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('Salary Read');
            Route::get('/history/{salary}', 'show')->name('show')->can('Salary Read');
        });