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
            Route::get('/{salary}', 'show')->name('show')->can('Salary Read');

            Route::controller(SalaryController::class)
                    ->prefix('monthly')
                    ->name('monthly.')
                    ->group(function () {
                        Route::get('/history', 'monthly')->name('history')->can('Salary Read');
                        Route::get('/history/{monthly_salary}', 'monthlyShow')->name('history.show')->can('Salary Read');
                    });
        });