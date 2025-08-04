<?php

namespace App\Models\Hiring\Relations;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use App\Models\Hiring\HiringCandidate;
use App\Models\Hiring\HiringStage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HiringStageEvaluationRelations
{
    /**
     * Get the candidate being evaluated
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(HiringCandidate::class, 'hiring_candidate_id');
    }

    /**
     * Get the stage being evaluated
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(HiringStage::class, 'hiring_stage_id');
    }

    /**
     * Get the user assigned to conduct this evaluation
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this evaluation
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this evaluation
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get files associated with this evaluation
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
