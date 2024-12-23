<?php

namespace App\Models\Attendance;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Attendance\Mutators\AttendanceMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attendance\Accessors\AttendanceAccessors;
use App\Models\Attendance\Relations\AttendanceRelations;

class Attendance extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use AttendanceRelations;

    // Accessors & Mutators
    use AttendanceAccessors, AttendanceMutators;
    
    protected $cascadeDeletes = ['daily_breaks'];

    protected $dates = [
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_shift_id',
        'clock_in_date',
        'clock_in',
        'clock_out',
        'total_time',
        'total_adjusted_time',
        'type',
        'clockin_medium',
        'clockout_medium',
        'clockin_scanner_id',
        'clockout_scanner_id',
        'ip_address',
        'country',
        'city',
        'zip_code',
        'time_zone',
        'latitude',
        'longitude'
    ];
}
