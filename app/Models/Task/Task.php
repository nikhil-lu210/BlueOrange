<?php

namespace App\Models\Task;

use App\Models\Task\Traits\TaskRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, TaskRelations;
    
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
