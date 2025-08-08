<?php

use App\Http\Controllers\Administration\Task\KanbanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {
    
    // Kanban Board Routes
    Route::prefix('kanban')->group(function () {
        
        // Get kanban data for the board
        Route::get('/data', [KanbanController::class, 'getKanbanData'])
             ->name('kanban.data');
        
        // Create new task
        Route::post('/tasks', [KanbanController::class, 'createTask'])
             ->name('kanban.tasks.create');
        
        // Update task status (for drag and drop)
        Route::put('/tasks/status', [KanbanController::class, 'updateTaskStatus'])
             ->name('kanban.tasks.update.status');
        
        // Delete task
        Route::delete('/tasks', [KanbanController::class, 'deleteTask'])
             ->name('kanban.tasks.delete');
        
        // Get specific task details
        Route::get('/tasks/{id}', [KanbanController::class, 'getTask'])
             ->name('kanban.tasks.get');
        
        // Update task details
        Route::put('/tasks/{id}', [KanbanController::class, 'updateTask'])
             ->name('kanban.tasks.update');
    });
});
