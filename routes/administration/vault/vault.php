<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Vault\VaultController;

/* ==============================================
===============< vault Routes >==============
===============================================*/
Route::controller(VaultController::class)->prefix('vault')->name('vault.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Vault Read');
    Route::get('/create', 'create')->name('create')->can('Vault Create');
    Route::post('/store', 'store')->name('store')->can('Vault Create');
    
    Route::get('/show/{vault}', 'show')->name('show');
    Route::get('/edit/{vault}', 'edit')->name('edit')->can('Vault Read');
    
    Route::put('/update/{vault}', 'update')->name('update')->can('Vault Update');

    Route::get('/destroy/{vault}', 'destroy')->name('destroy')->can('Vault Delete');
    
    Route::get('/export', 'export')->name('export')->can('Vault Read');
});