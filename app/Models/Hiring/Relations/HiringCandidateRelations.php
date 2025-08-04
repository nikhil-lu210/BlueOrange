<?php

namespace App\Models\Hiring\Relations;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use App\Models\Hiring\HiringStageEvaluation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HiringCandidateRelations
{
    /**
     * Get the user who created this candidate record
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user account created after hiring
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all stage evaluations for this candidate
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class);
    }

    /**
     * Get evaluations ordered by stage
     */
    public function orderedEvaluations(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class)
            ->join('hiring_stages', 'hiring_stage_evaluations.hiring_stage_id', '=', 'hiring_stages.id')
            ->orderBy('hiring_stages.stage_order');
    }

    /**
     * Get the current stage evaluation
     */
    public function currentStageEvaluation(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class)
            ->join('hiring_stages', 'hiring_stage_evaluations.hiring_stage_id', '=', 'hiring_stages.id')
            ->where('hiring_stages.stage_order', $this->current_stage);
    }

    /**
     * Get files associated with this candidate (resume, etc.)
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
