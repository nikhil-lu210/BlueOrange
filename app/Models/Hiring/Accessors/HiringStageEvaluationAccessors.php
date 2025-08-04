<?php

namespace App\Models\Hiring\Accessors;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HiringStageEvaluationAccessors
{
    /**
     * Get the status badge class
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'pending' => 'bg-secondary',
                    'in_progress' => 'bg-warning',
                    'completed' => 'bg-info',
                    'passed' => 'bg-success',
                    'failed' => 'bg-danger',
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
     * Get the duration of evaluation
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->started_at || !$this->completed_at) {
                    return null;
                }
                
                $duration = $this->completed_at->diff($this->started_at);
                
                if ($duration->days > 0) {
                    return $duration->days . ' days, ' . $duration->h . ' hours';
                } elseif ($duration->h > 0) {
                    return $duration->h . ' hours, ' . $duration->i . ' minutes';
                } else {
                    return $duration->i . ' minutes';
                }
            }
        );
    }

    /**
     * Get the rating with stars
     */
    protected function ratingStars(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->rating) {
                    return 'Not rated';
                }
                
                $stars = str_repeat('★', $this->rating) . str_repeat('☆', 10 - $this->rating);
                return $stars . ' (' . $this->rating . '/10)';
            }
        );
    }
}
