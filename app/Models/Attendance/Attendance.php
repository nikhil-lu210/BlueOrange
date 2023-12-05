<?php

namespace App\Models\Attendance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];
    protected $dates = ['clock_in', 'clock_out', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
        'ip_address',
        'country',
        'city',
        'zip_code',
        'time_zone'
    ];

    public function setClockInAttribute($value)
    {
        $this->attributes['clock_in'] = Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    public function setClockOutAttribute($value)
    {
        $this->attributes['clock_out'] = $value ? Carbon::parse($value)->setTimezone(config('app.timezone')) : null;
    }
}
