<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\EmployeeRecognition\EmployeeRecognitionController;

Route::prefix('recognition')
    ->name('employee_recognition.')
    ->controller(EmployeeRecognitionController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->can('Recognition Create');
        Route::post('/', 'store')->name('store')->can('Recognition Create');
        Route::get('/leaderboard', 'leaderboard')->name('leaderboard')->can('Recognition Everything');
        Route::get('/my', 'myScores')->name('my')->can('Recognition Read');
        Route::get('/reports', 'reports')->name('reports')->can('Recognition Everything');
        Route::get('/trend/{user}', 'employeeTrend')->name('employee.trend')->can('Recognition Create');
    });
