<?php

namespace App\Models\Task;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Task\Traits\TaskHistoryRelations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskHistory extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, TaskHistoryRelations;
    
    protected $cascadeDeletes = ['files'];

    protected $fillable = [
        'task_id',
        'user_id',
        'started_at',
        'ends_at',
        'total_worked',
        'note',
        'progress',
        'status',
    ];

    protected $dates = [
        'started_at', 
        'ends_at', 
        'deleted_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            // Store the history's user id
            if (auth()->check()) {
                $history->user_id = auth()->user()->id;
            }

            // Store started_at current date-time
            $history->started_at = now();
        });
    }
}
