<?php

namespace App\Models\Attendance;

use Carbon\Carbon;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;
    
    protected $cascadeDeletes = ['daily_breaks'];
    protected $dates = ['clock_in', 'clock_out', 'deleted_at'];
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
        'qr_clockin_scanner_id',
        'qr_clockout_scanner_id',
        'ip_address',
        'country',
        'city',
        'zip_code',
        'time_zone',
        'latitude',
        'longitude'
    ];

    public function setClockInAttribute($value)
    {
        $this->attributes['clock_in'] = Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    public function setClockOutAttribute($value)
    {
        $this->attributes['clock_out'] = $value ? Carbon::parse($value)->setTimezone(config('app.timezone')) : null;
    }

    /**
     * Get the total number of breaks taken for the attendance.
     */
    public function getTotalBreaksTakenAttribute(): int
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->count();
    }

    /**
     * Get the total break time for the attendance.
     */
    public function getTotalBreakTimeAttribute()
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))) as total_break_time')
            ->value('total_break_time') ?? NULL;
    }

    /**
     * Get the total over break time for the attendance.
     */
    public function getTotalOverBreakAttribute()
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(over_break))) as total_over_break')
            ->value('total_over_break') ?? NULL;
    }
}
