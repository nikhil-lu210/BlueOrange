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
        Route::get('/create', 'create')->name('create')->can('App Setting Create');
        Route::post('/store', 'store')->name('store')->can('App Setting Create');
        Route::get('/{translation}/show', 'show')->name('show')->can('App Setting Read');
        Route::get('/{translation}/edit', 'edit')->name('edit')->can('App Setting Update');
        Route::put('/{translation}/update', 'update')->name('update')->can('App Setting Update');
        Route::delete('/{translation}/destroy', 'destroy')->name('destroy')->can('App Setting Delete');
    });

/* ==============================================
===============< Language Switching Routes >==============
===============================================*/
Route::controller(TranslationController::class)
    ->group(function () {
        Route::get('/localization/{lang}', 'switchLanguage')->name('localization');
    });

