<?php

use App\Http\Controllers\Administration\Inventory\InventoryController;
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
            Route::get('/show/{inventory}', 'show')->name('show')->can('Inventory Read');
            Route::get('/destroy/{inventory}', 'destroy')->name('destroy')->can('Inventory Delete');
        });

    // Inventory Category
    Route::prefix('category')
    ->name('category.')
    ->group(function () {
        Route::controller(InventoryController::class)
            ->group(function () {
                Route::get('/all', 'inventoryCategory')->name('index');
            });
    });
});
