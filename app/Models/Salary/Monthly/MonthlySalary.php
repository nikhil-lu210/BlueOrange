<?php

namespace App\Models\Salary\Monthly;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Salary\Monthly\Traits\Relations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySalary extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'user_id',
    ];
}
