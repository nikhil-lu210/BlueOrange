<?php

namespace App\Models\Task;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task\Traits\TaskRelations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, TaskRelations;
    
    protected $cascadeDeletes = ['task_user'];

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status'
    ];
}
