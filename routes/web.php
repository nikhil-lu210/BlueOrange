<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();


/*==============================================================
======================< Public Routes >=================
==============================================================*/
Route::middleware(['web'])->group(function () {
    include_once 'application/application.php';

    // CSRF token refresh route
    Route::get('/csrf-refresh', [\App\Http\Controllers\CsrfController::class, 'refresh'])->name('csrf.refresh');
});


/*==============================================================
======================< Administration Routes >=================
==============================================================*/
Route::middleware(['auth', 'active_user'])->group(function () {
    include_once 'administration/administration.php';

    include_once 'custom_auth/custom_auth.php';
});
