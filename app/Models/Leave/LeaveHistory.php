<?php

namespace App\Models\Leave;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Leave\Mutators\LeaveHistoryMutators;
use App\Models\Leave\Accessors\LeaveHistoryAccessors;
use App\Models\Leave\Relations\LeaveHistoryRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveHistory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use LeaveHistoryRelations;

    // Accessors & Mutators
    use LeaveHistoryAccessors, LeaveHistoryMutators;
    
    protected $cascadeDeletes = [];
    
    // Casting attributes
    protected $casts = [
        'date' => 'date',
        'reason' => PurifyHtmlOnGet::class,
        'reviewer_note' => PurifyHtmlOnGet::class,
        'is_paid_leave' => 'boolean',
    ];

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'leave_allowed_id',
        'date',
        'total_leave',
        'type',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'reviewer_note',
        'is_paid_leave',
    ];
}
