<?php

use App\Http\Controllers\Administration\Localization\LocalizationController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Localization Routes >==============
===============================================*/
Route::get('localization/{lang}', LocalizationController::class)->name('localization');