<?php

namespace App\Models\DailyBreak;

use App\Models\DailyBreak\Traits\Relations;
use Carbon\Carbon;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyBreak extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId, Relations;
    
    protected $cascadeDeletes = [];
    protected $dates = ['break_in_at', 'break_out_at', 'deleted_at'];
    protected $casts = [
        'date' => 'date',
        'break_in_at' => 'datetime',
        'break_out_at' => 'datetime',
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
        'type',
        'break_in_ip',
        'break_out_ip',
        'note',
    ];

    public function setClockInAttribute($value)
    {
        $this->attributes['break_in_at'] = Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    public function setClockOutAttribute($value)
    {
        $this->attributes['break_out_at'] = $value ? Carbon::parse($value)->setTimezone(config('app.timezone')) : null;
    }
}
