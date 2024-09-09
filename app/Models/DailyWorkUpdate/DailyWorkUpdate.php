<?php

namespace App\Models\DailyWorkUpdate;

use App\Models\DailyWorkUpdate\Traits\Relations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyWorkUpdate extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'user_id',
        'team_leader_id',
        'date',
        'work_update',
        'progress',
        'note',
        'rating',
        'comment',
    ];

    protected $dates = ['date', 'deleted_at'];
    
    protected $casts = [
        'work_update' => PurifyHtmlOnGet::class,
        'note' => PurifyHtmlOnGet::class,
        // 'comment' => PurifyHtmlOnGet::class,
        'date' => 'date'
    ];

    protected $with = ['user', 'team_leader'];
}
