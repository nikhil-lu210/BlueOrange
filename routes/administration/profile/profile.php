<?php

use App\Http\Controllers\Administration\Profile\ProfileController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Profile Routes >==============
===============================================*/
Route::controller(ProfileController::class)->prefix('my')->name('my.')->group(function () {
    Route::get('/profile', 'profile')->name('profile');
    
    Route::get('/profile/edit', 'edit')->name('profile.edit');
    Route::post('/profile/update', 'update')->name('profile.update');
    
    Route::get('/attendance', 'attendance')->name('attendance');
    Route::get('/break', 'break')->name('break');
});