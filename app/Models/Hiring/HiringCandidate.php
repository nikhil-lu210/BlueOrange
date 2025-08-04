<?php

namespace App\Models\Hiring;

use App\Traits\HasCustomRouteId;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Hiring\Relations\HiringCandidateRelations;
use App\Models\Hiring\Accessors\HiringCandidateAccessors;
use App\Models\Hiring\Mutators\HiringCandidateMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\Hiring\HiringCandidateObserver;

#[ObservedBy([HiringCandidateObserver::class])]
class HiringCandidate extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, HasCustomRouteId;

    // Relations
    use HiringCandidateRelations;

    // Accessors & Mutators
    use HiringCandidateAccessors, HiringCandidateMutators;

    protected $cascadeDeletes = ['evaluations', 'files'];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'hired_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'expected_role',
        'expected_salary',
        'notes',
        'status',
        'current_stage',
        'created_by',
        'user_id',
        'hired_at',
    ];

    /**
     * Get the available statuses
     */
    public static function getStatuses(): array
    {
        return [
            'shortlisted' => 'Shortlisted',
            'in_progress' => 'In Progress',
            'rejected' => 'Rejected',
            'hired' => 'Hired'
        ];
    }

    /**
     * Get the stage names
     */
    public static function getStageNames(): array
    {
        return [
            1 => 'Basic Interview',
            2 => 'Workshop',
            3 => 'Final Interview'
        ];
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::creating(function ($candidate) {
            if (auth()->check() && is_null($candidate->created_by)) {
                $candidate->created_by = auth()->id();
            }
        });
    }
}
