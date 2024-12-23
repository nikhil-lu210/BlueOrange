<?php

namespace App\Models\DailyBreak;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\DailyBreak\Mutators\DailyBreakMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DailyBreak\Accessors\DailyBreakAccessors;
use App\Models\DailyBreak\Relations\DailyBreakRelations;

class DailyBreak extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use DailyBreakRelations;

    // Accessors & Mutators
    use DailyBreakAccessors, DailyBreakMutators;
    
    protected $cascadeDeletes = [];

    protected $dates = [
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'attendance_id' => 'integer',
        'date' => 'date',
        'break_in_at' => 'datetime',
        'break_out_at' => 'datetime',
        'total_time' => 'string',
        'over_break' => 'string',
        'type' => 'string',
        'break_in_ip' => 'string',
        'break_out_ip' => 'string',
        'note' => PurifyHtmlOnGet::class,
    ];

    protected $with = ['user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'break_in_at',
        'break_out_at',
        'total_time',
        'over_break',
        'type',
        'break_in_ip',
        'break_out_ip',
        'note',
    ];
}
