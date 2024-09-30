<?php

namespace App\Models\Chatting;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Chatting\Traits\ChattingGroupRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChattingGroup extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, ChattingGroupRelations, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'groupid',
        'name',
        'creator_id',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            // Combine 'CGID', timestamp
            $group->groupid = 'CGID' . strtoupper(now()->format('YmdHis'));

            // Store the group creator id
            if (auth()->check()) {
                $group->creator_id = auth()->user()->id;
            }
        });

        static::deleting(function ($group) {
            // If soft deleting, ensure group messages are also soft deleted
            if ($group->isForceDeleting()) {
                $group->group_messages()->forceDelete();
            } else {
                $group->group_messages()->delete();
            }
        });
    }
}
