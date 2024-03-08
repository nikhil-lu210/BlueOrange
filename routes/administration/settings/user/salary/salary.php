<?php

use App\Http\Controllers\Administration\Settings\User\Salary\SalaryController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Salary Routes >==============
===============================================*/
Route::controller(SalaryController::class)
        ->prefix('{user}/salary')
        ->name('salary.')
        ->group(function () {
            Route::get('/history', 'index')->name('index')->can('Salary Read');
            Route::get('/history/{salary}', 'show')->name('show')->can('Salary Read');
            Route::post('/history/{salary}/update', 'update')->name('update')->can('Salary Update');
            
            Route::get('/create', 'create')->name('create')->can('Salary Create');
            Route::post('/store', 'store')->name('store')->can('Salary Create');
            
            // monthly
            include_once 'monthly/monthly.php';
        });