<?php

namespace App\Models\Hiring;

use App\Traits\HasCustomRouteId;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Hiring\Relations\HiringStageEvaluationRelations;
use App\Models\Hiring\Accessors\HiringStageEvaluationAccessors;
use App\Models\Hiring\Mutators\HiringStageEvaluationMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HiringStageEvaluation extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, HasCustomRouteId;

    // Relations
    use HiringStageEvaluationRelations;

    // Accessors & Mutators
    use HiringStageEvaluationAccessors, HiringStageEvaluationMutators;

    protected $cascadeDeletes = ['files'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'hiring_candidate_id',
        'hiring_stage_id',
        'assigned_to',
        'status',
        'notes',
        'feedback',
        'rating',
        'assigned_at',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the available statuses
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'passed' => 'Passed',
            'failed' => 'Failed'
        ];
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::creating(function ($evaluation) {
            if (auth()->check() && is_null($evaluation->created_by)) {
                $evaluation->created_by = auth()->id();
            }
            if (is_null($evaluation->assigned_at)) {
                $evaluation->assigned_at = now();
            }
        });

        static::updating(function ($evaluation) {
            if (auth()->check()) {
                $evaluation->updated_by = auth()->id();
            }
        });
    }
}
