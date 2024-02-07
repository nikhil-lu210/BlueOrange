<?php

namespace App\Models\WorkingShift;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkingShift\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory, Relations, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];
}
