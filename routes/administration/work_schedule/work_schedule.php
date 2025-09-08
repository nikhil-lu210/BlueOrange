<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\WorkSchedule\WorkScheduleController;

/*
|--------------------------------------------------------------------------
| Work Schedule Routes
|--------------------------------------------------------------------------
|
| Here is where you can register work schedule routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Work Schedule Routes
Route::prefix('work_schedule')->name('work_schedule.')->group(function () {
    // Main CRUD routes
    Route::get('/', [WorkScheduleController::class, 'index'])->name('index');
    Route::get('/create', [WorkScheduleController::class, 'create'])->name('create');
    Route::post('/', [WorkScheduleController::class, 'store'])->name('store');
    Route::get('/{workSchedule}', [WorkScheduleController::class, 'show'])->name('show');
    Route::get('/{workSchedule}/edit', [WorkScheduleController::class, 'edit'])->name('edit');
    Route::put('/{workSchedule}', [WorkScheduleController::class, 'update'])->name('update');
    Route::delete('/{workSchedule}', [WorkScheduleController::class, 'destroy'])->name('destroy');

    // Additional routes
    Route::get('/report/graph', [WorkScheduleController::class, 'report'])->name('report');
    Route::get('/ajax/user-shift', [WorkScheduleController::class, 'getUserShift'])->name('get-user-shift');
});
