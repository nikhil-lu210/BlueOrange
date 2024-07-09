<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TaskHistoryRelations
{
    /**
     * Get the task for the TaskHistory.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    /**
     * Get the user for the TaskHistory.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}