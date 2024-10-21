<?php

namespace App\Models\Leave;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Leave\Relations\LeaveAvailableRelations;

class LeaveAvailable extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, LeaveAvailableRelations, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $casts = [
        'for_year' => 'year',
    ];

    protected $fillable = [
        'user_id',
        'for_year',
        'earned_leave',
        'casual_leave',
        'sick_leave'
    ];
}
