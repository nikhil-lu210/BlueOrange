<?php

namespace App\Models\Leave;

use App\Models\Leave\Accessors\LeaveAvailableAccessors;
use App\Models\Leave\Mutators\LeaveAvailableMutators;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Leave\Relations\LeaveAvailableRelations;

class LeaveAvailable extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use LeaveAvailableRelations;

    // Accessors & Mutators
    use LeaveAvailableAccessors, LeaveAvailableMutators;

    protected $cascadeDeletes = [];
    
    // Casting attributes
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'for_year',
        'earned_leave',
        'casual_leave',
        'sick_leave',
    ];
}
