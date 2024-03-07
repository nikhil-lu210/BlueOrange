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
            Route::get('/{user}/history', 'index')->name('index')->can('Salary Create');
            Route::get('/{user}/history/{salary}', 'show')->name('show')->can('Salary Create');
            Route::post('/{user}/history/{salary}/update', 'update')->name('update')->can('Salary Update');
            
            Route::get('/{user}/create', 'create')->name('create')->can('Salary Create');
        });