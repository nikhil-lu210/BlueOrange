<?php

use Illuminate\Support\Facades\Route;

/* ==============================================
============< Public Routes >============
===============================================*/
Route::prefix('application')
->name('application.')
->group(function () {
    // accounts
    include_once 'accounts/accounts.php';
});