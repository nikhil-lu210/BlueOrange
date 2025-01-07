<?php

use App\Http\Controllers\Administration\Settings\System\AppSetting\RestrictionController;
use Illuminate\Support\Facades\Route;


/* ==============================================
===============< Restriction Routes >==============
===============================================*/
Route::controller(RestrictionController::class)
        ->prefix('restrictions')
        ->name('restriction.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->can('App Setting Update');
            
            Route::put('/update/device', 'updateDeviceRestriction')->name('update.device')->can('App Setting Update');

            Route::put('/update/ip_range', 'updateIpRange')->name('update.ip.range')->can('App Setting Update');
            Route::get('/destroy/ip_range/{id}', 'destroyIpRange')->name('destroy.ip.range')->can('App Setting Delete');
        });