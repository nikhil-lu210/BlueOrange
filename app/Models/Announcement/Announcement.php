<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;
use App\Models\Announcement\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'announcer_id',
        'recipients',
        'title',
        'description',
        'read_by_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'read_by_at' => 'array',
    ];
}
