<?php

use App\Http\Controllers\Administration\Ticket\ItTicketController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< It Ticket Routes >==============
===============================================*/
Route::controller(ItTicketController::class)->prefix('ticket/it_ticket')->name('ticket.it_ticket.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('IT Ticket Read');
    Route::get('/my', 'my')->name('my')->can('IT Ticket Read');
    Route::get('/create', 'create')->name('create')->can('IT Ticket Create');

    Route::post('/store', 'store')->name('store')->can('IT Ticket Create');
    
    Route::get('/show/{it_ticket}', 'show')->name('show');
    Route::get('/edit/{it_ticket}', 'edit')->name('edit')->can('IT Ticket Read');
    
    Route::put('/update/{it_ticket}', 'update')->name('update')->can('IT Ticket Update');

    Route::get('/destroy/{it_ticket}', 'destroy')->name('destroy')->can('IT Ticket Delete');
    
    Route::get('/mark-as-running/{it_ticket}', 'markAsRunning')->name('mark.running')->can('IT Ticket Update');
    Route::put('/update-status/{it_ticket}', 'updateStatus')->name('update.status')->can('IT Ticket Update');
    
    Route::get('/export', 'export')->name('export')->can('IT Ticket Read');
});