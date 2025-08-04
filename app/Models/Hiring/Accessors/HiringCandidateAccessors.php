<?php

namespace App\Models\Hiring\Accessors;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HiringCandidateAccessors
{
    /**
     * Get the status badge class
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'shortlisted' => 'bg-info',
                    'in_progress' => 'bg-warning',
                    'rejected' => 'bg-danger',
                    'hired' => 'bg-success',
                    default => 'bg-secondary',
                };
            }
        );
    }

    /**
     * Get the formatted status
     */
    protected function statusFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ucfirst(str_replace('_', ' ', $this->status));
            }
        );
    }

    /**
     * Get the current stage name
     */
    protected function currentStageName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return self::getStageNames()[$this->current_stage] ?? 'Unknown';
            }
        );
    }

    /**
     * Get the formatted expected salary
     */
    protected function expectedSalaryFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->expected_salary ? '₹' . number_format($this->expected_salary, 2) : 'Not specified';
            }
        );
    }

    /**
     * Get the progress percentage
     */
    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'hired') {
                    return 100;
                }
                if ($this->status === 'rejected') {
                    return 0;
                }
                return ($this->current_stage / 3) * 100;
            }
        );
    }
}
