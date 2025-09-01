<?php

use App\Http\Controllers\Administration\Inventory\InventoryController;
use App\Http\Controllers\Administration\Inventory\InventoryImportController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< inventory Routes >================
===============================================*/
Route::prefix('inventory')
->name('inventory.')
->group(function () {
    Route::controller(InventoryController::class)
        ->group(function () {
            Route::get('/all', 'index')->name('index')->can('Inventory Everything');
            Route::get('/create', 'create')->name('create')->can('Inventory Create');
            Route::post('/store', 'store')->name('store')->can('Inventory Create');
            Route::get('/all/{inventory}/show', 'show')->name('show')->can('Inventory Read');
            Route::get('/all/{inventory}/edit', 'edit')->name('edit')->can('Inventory Update');
            Route::put('/update/{inventory}', 'update')->name('update')->can('Inventory Update');
            Route::put('/status/update/{inventory}', 'statusUpdate')->name('status.update')->can('Inventory Update');
                        Route::get('/destroy/{inventory}', 'destroy')->name('destroy')->can('Inventory Delete');
            Route::get('/export', 'export')->name('export')->can('Inventory Read');
 
        });

    Route::controller(InventoryImportController::class)->prefix('import')->name('import.')->group(function () {
        Route::get('/', 'index')->name('index')->can('Inventory Create');
        Route::post('/upload', 'upload')->name('upload')->can('Inventory Create');
    });
});
