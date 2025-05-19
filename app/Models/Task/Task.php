<?php

namespace App\Models\Task;

use App\Traits\HasCustomRouteId;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task\Traits\TaskRelations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, TaskRelations, HasCustomRouteId;

    protected $cascadeDeletes = ['histories', 'comments', 'files'];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
        'deadline' => 'date'
    ];

    protected $fillable = [
        'taskid',
        'chatting_id',
        'parent_task_id',
        'creator_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            // Combine 'BOT', timestamp
            $task->taskid = 'BOT' . now()->format('YmdHis');

            // Store the task creator id
            if (auth()->check()) {
                $task->creator_id = auth()->user()->id;
            }
        });
    }
}
