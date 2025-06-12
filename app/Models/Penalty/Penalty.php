<?php

namespace App\Models\Penalty;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Penalty\Mutators\PenaltyMutators;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Penalty\Accessors\PenaltyAccessors;
use App\Models\Penalty\Relations\PenaltyRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\Penalty\PenaltyObserver;

#[ObservedBy([PenaltyObserver::class])]
class Penalty extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use PenaltyRelations;

    // Accessors & Mutators
    use PenaltyAccessors, PenaltyMutators;

    protected $cascadeDeletes = [];

    // Casting attributes
    protected $casts = [
        'reason' => 'string',
    ];

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'attendance_id',
        'type',
        'total_time',
        'reason',
        'creator_id',
    ];

    /**
     * Get the penalty types as an array
     */
    public static function getPenaltyTypes(): array
    {
        return [
            'Dress Code Violation',
            'Unauthorized Break',
            'Bad Attitude',
            'Unexcused Absence',
            'Unauthorized Leave',
            'Unauthorized Overtime',
            'Other'
        ];
    }
}
