<?php

namespace App\Models\Chatting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Chatting\Traits\ChattingGroupRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChattingGroup extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, ChattingGroupRelations;
    
    protected $cascadeDeletes = ['group_chattings'];

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
    }
}
