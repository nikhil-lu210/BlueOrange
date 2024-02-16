<?php

namespace App\Models\EmployeeShift;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeShift\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeShift extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes;
    
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
