<?php

namespace App\Models\Leave;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Leave\Mutators\LeaveAllowedMutators;
use App\Models\Leave\Accessors\LeaveAllowedAccessors;
use App\Models\Leave\Relations\LeaveAllowedRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAllowed extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use LeaveAllowedRelations;

    // Accessors & Mutators
    use LeaveAllowedAccessors, LeaveAllowedMutators;

    // Casting attributes
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'earned_leave',
        'casual_leave',
        'sick_leave',
        'implemented_from',
        'implemented_to',
        'is_active',
    ];
}
