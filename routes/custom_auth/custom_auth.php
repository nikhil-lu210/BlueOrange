<?php

use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Custom Routes >==============
===============================================*/
Route::prefix('custom_auth')->name('custom_auth.')->group(function () {
    // impersonate
    include_once 'impersonate/impersonate.php';
});
