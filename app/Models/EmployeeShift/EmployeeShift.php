<?php

namespace App\Models\EmployeeShift;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\EmployeeShift\Traits\Relations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeShift extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'implemented_from',
        'implemented_to',
        'status'
    ];
}
