<?php

use App\Http\Controllers\Administration\Settings\User\Salary\SalaryController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Salary Routes >==============
===============================================*/
Route::controller(SalaryController::class)
        ->prefix('salary')
        ->name('salary.')
        ->group(function () {
            Route::get('/{user}/history', 'index')->name('index')->can('User Create');
        });