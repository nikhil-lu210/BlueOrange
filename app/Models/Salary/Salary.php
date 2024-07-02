<?php

namespace App\Models\Salary;

use App\Models\Salary\Traits\Relations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'user_id',
        'basic_salary',
        'house_benefit',
        'transport_allowance',
        'medical_allowance',
        'night_shift_allowance',
        'other_allowance',
        'implemented_from',
        'implemented_to',
        'total',
        'status'
    ];
}
