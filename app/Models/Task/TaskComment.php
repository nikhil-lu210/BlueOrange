<?php

namespace App\Models\Task;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use App\Models\Task\Traits\TaskCommentRelations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskComment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, TaskCommentRelations;
    
    protected $cascadeDeletes = ['files'];

    protected $casts = [
        'comment' => PurifyHtmlOnGet::class,
    ];

    protected $fillable = [
        'task_id',
        'user_id',
        'comment'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            // Store the comment's user id
            if (auth()->check()) {
                $comment->user_id = auth()->user()->id;
            }
        });
    }
}
