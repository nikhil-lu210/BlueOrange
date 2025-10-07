<?php

use App\Http\Controllers\Administration\Settings\System\AppSetting\TranslationController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< Translation Routes >==============
===============================================*/
Route::controller(TranslationController::class)
    ->prefix('translation')
    ->name('translation.')
    ->group(function () {
        Route::get('/all', 'index')->name('index')->can('App Setting Read');
        Route::put('/{translation}/update', 'update')->name('update')->can('App Setting Update');
        Route::get('/{translation}/destroy', 'destroy')->name('destroy')->can('App Setting Delete');
    });

/* ==============================================
===============< Language Switching Routes >==============
===============================================*/
Route::controller(TranslationController::class)
    ->group(function () {
        Route::get('/localization/{lang}', 'switchLanguage')->name('localization');
    });

