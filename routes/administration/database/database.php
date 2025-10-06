<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\Database\DatabaseBackupController;

/*
|--------------------------------------------------------------------------
| Database Routes
|--------------------------------------------------------------------------
|
| Here are the database-related routes for the administration panel.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "administration" middleware group.
|
*/

// Database Backup Routes
Route::prefix('database')->name('database.')->group(function () {

    // Backup download route (accessible via email links)
    Route::get('/backup/download/{filename}', [DatabaseBackupController::class, 'downloadBackup'])
         ->name('backup.download');

    // List available backups (for admin management)
    Route::get('/backup/list', [DatabaseBackupController::class, 'listBackups'])
         ->name('backup.list');
});
