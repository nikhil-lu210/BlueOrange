<?php

use App\Http\Controllers\Administration\FileMedia\FileMediaController;
use Illuminate\Support\Facades\Route;

/* ==============================================
===============< FileMedia Routes >==============
===============================================*/
Route::controller(FileMediaController::class)->prefix('file')->name('file.')->group(function () {
    Route::get('/file/download/{fileMedia}', 'download')->name('download');
});