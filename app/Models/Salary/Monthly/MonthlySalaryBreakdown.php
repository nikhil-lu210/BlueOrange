<?php

namespace App\Models\Salary\Monthly;

use App\Models\Salary\Monthly\Traits\MonthlySalaryBreakdownRelations;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySalaryBreakdown extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId, MonthlySalaryBreakdownRelations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'monthly_salary_id',
        'type',
        'reason',
        'total',
    ];
}
