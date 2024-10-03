<?php

namespace App\Models\Salary\Monthly;

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

    protected $with = [
        'user',
        'salary',
        'monthly_salary_breakdowns',
    ];

    protected $fillable = [
        'user_id',
        'salary_id',
        'for_month',
        'total_payable',
        'status',
    ];
}
