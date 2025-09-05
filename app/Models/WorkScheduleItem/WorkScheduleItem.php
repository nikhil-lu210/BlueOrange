<?php

namespace App\Models\WorkScheduleItem;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\WorkScheduleItem\Mutators\WorkScheduleItemMutators;
use App\Models\WorkScheduleItem\Accessors\WorkScheduleItemAccessors;
use App\Models\WorkScheduleItem\Relations\WorkScheduleItemRelations;
use App\Models\WorkScheduleItem\Scopes\WorkScheduleItemScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\WorkScheduleItem\WorkScheduleItemObserver;

#[ObservedBy([WorkScheduleItemObserver::class])]
class WorkScheduleItem extends Model
{
    use HasFactory, SoftDeletes, HasCustomRouteId;

    // Relations
    use WorkScheduleItemRelations;

    // Accessors & Mutators
    use WorkScheduleItemAccessors, WorkScheduleItemMutators;

    // Scopes
    use WorkScheduleItemScopes;

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'duration_minutes' => 'integer',
    ];

    protected $fillable = [
        'work_schedule_id',
        'start_time',
        'end_time',
        'work_type',
        'work_title',
        'duration_minutes',
    ];

    /**
     * Get work types for validation
     */
    public static function getWorkTypes(): array
    {
        return ['Client', 'Internal', 'Bench'];
    }
}
