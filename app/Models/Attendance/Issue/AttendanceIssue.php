<?php

namespace App\Models\Attendance\Issue;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attendance\Issue\Mutators\AttendanceIssueMutators;
use App\Models\Attendance\Issue\Accessors\AttendanceIssueAccessors;
use App\Models\Attendance\Issue\Relations\AttendanceIssueRelations;

class AttendanceIssue extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use AttendanceIssueRelations;

    // Accessors & Mutators
    use AttendanceIssueAccessors, AttendanceIssueMutators;
    
    protected $cascadeDeletes = [];

    protected $dates = [
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    protected $casts = [
        'clock_in_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'reason' => PurifyHtmlOnGet::class,
        'note' => PurifyHtmlOnGet::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'attendance_id',
        'employee_shift_id',
        'updated_by',
        'title',
        'clock_in_date',
        'clock_in',
        'clock_out',
        'type',
        'reason',
        'status',
        'note',
    ];
}
