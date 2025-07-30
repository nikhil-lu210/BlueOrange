<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Certificate\CertificateController;

/* ==============================================
===============< Certificate Routes >================
===============================================*/
Route::prefix('certificate')
    ->name('certificate.')
    ->group(function () {
        Route::controller(CertificateController::class)
            ->group(function () {
                Route::get('/all', 'index')->name('index')->can('Certificate Everything');
                Route::get('/my', 'my')->name('my')->can('Certificate Read');
                Route::get('/create', 'create')->name('create')->can('Certificate Create');
                Route::get('/generate', 'generate')->name('generate')->can('Certificate Create');
                Route::post('/store', 'store')->name('store')->can('Certificate Create');
                Route::get('/show/{certificate}', 'show')->name('show')->can('Certificate Read');
                Route::get('/destroy/{certificate}', 'destroy')->name('destroy')->can('Certificate Delete');
            });
    });
