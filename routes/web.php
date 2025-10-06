<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsrfController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'reset' => false, // Disable default password reset routes
]);

// Custom password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/sent', [ForgotPasswordController::class, 'showLinkSentPage'])->name('password.sent');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


/*==============================================================
======================< Public Routes >=================
==============================================================*/
Route::middleware(['web'])->group(function () {
    include_once 'application/application.php';

    // CSRF token refresh route
    Route::get('/csrf-refresh', [CsrfController::class, 'refresh'])->name('csrf.refresh');
});


/*==============================================================
======================< Administration Routes >=================
==============================================================*/
Route::middleware(['auth', 'active_user'])->group(function () {
    include_once 'administration/administration.php';

    include_once 'custom_auth/custom_auth.php';
});
