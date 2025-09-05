<?php

namespace App\Models\WorkSchedule;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\WorkSchedule\Mutators\WorkScheduleMutators;
use App\Models\WorkSchedule\Accessors\WorkScheduleAccessors;
use App\Models\WorkSchedule\Relations\WorkScheduleRelations;
use App\Models\WorkSchedule\Scopes\WorkScheduleScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\WorkSchedule\WorkScheduleObserver;

#[ObservedBy([WorkScheduleObserver::class])]
class WorkSchedule extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use WorkScheduleRelations;

    // Accessors & Mutators
    use WorkScheduleAccessors, WorkScheduleMutators;

    // Scopes
    use WorkScheduleScopes;

    protected $cascadeDeletes = ['work_schedule_items'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'employee_shift_id',
        'weekday',
        'is_active',
    ];

    /**
     * Get work types for validation
     */
    public static function getWorkTypes(): array
    {
        return ['Client', 'Internal', 'Bench'];
    }

    /**
     * Get weekdays
     */
    public static function getWeekdays(): array
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}
