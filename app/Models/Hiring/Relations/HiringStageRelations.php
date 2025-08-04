<?php

namespace App\Models\Hiring\Relations;

use App\Models\Hiring\HiringStageEvaluation;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HiringStageRelations
{
    /**
     * Get all evaluations for this stage
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class);
    }

    /**
     * Get pending evaluations for this stage
     */
    public function pendingEvaluations(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class)
            ->where('status', 'pending');
    }

    /**
     * Get completed evaluations for this stage
     */
    public function completedEvaluations(): HasMany
    {
        return $this->hasMany(HiringStageEvaluation::class)
            ->whereIn('status', ['completed', 'passed', 'failed']);
    }
}
