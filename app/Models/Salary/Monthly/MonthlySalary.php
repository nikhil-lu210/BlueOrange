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
        'payslip_id', // unique
        'user_id', // from users table
        'salary_id', // from salaries table
        'for_month', // Y-m format
        'total_workable_days', // tinyInteger
        'total_weekends', // tinyInteger
        'total_holidays', // tinyInteger (nullable)
        'hourly_rate', // float (8,2)
        'total_payable', // float (8,2)
        'paid_by', // from users table (nullable)
        'paid_through', // nullable
        'payment_proof', // nullable
        'paid_at', // nullable
        'status', // enum ['Paid', 'Pending', 'Canceled'] (default = Pending)
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
