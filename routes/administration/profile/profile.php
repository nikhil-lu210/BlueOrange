<?php

use App\Http\Controllers\Administration\Profile\ProfileController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Profile Routes >==============
===============================================*/
Route::controller(ProfileController::class)->prefix('my')->name('my.')->group(function () {
    Route::get('/profile', 'profile')->name('profile');
    Route::get('/profile/security', 'security')->name('profile.security');
    Route::post('/profile/security/password/update', 'updatePassword')->name('profile.security.password.update');
    
    Route::get('/profile/edit', 'edit')->name('profile.edit');
    Route::post('/profile/update', 'update')->name('profile.update');
    
    // salary
    include_once 'salary/salary.php';
});