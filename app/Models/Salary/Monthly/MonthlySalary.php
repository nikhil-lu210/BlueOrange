<?php

namespace App\Models\Salary\Monthly;

use Carbon\Carbon;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Salary\Monthly\Traits\Relations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySalary extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'payslip_id',
        'user_id',
        'salary_id',
        'for_month',
        'total_workable_days',
        'total_weekends',
        'total_holidays',
        'hourly_rate',
        'total_payable',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($monthlySalary) {
            // Extract year and month from for_month
            $formattedDate = Carbon::parse($monthlySalary->for_month)->format('Ym'); // Format as Ym

            // Get the user ID without the UID prefix
            $userId = str_replace('UID', '', $monthlySalary->user->userid);

            // Generate a unique payslip_id with PID prefix
            $monthlySalary->payslip_id = 'PID' . $userId . $formattedDate;
        });
    }
}
