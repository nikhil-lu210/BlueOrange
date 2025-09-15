<?php

namespace App\Models\Recognition\Accessors;

trait RecognitionAccessors
{
    protected function categoryColors(): array
    {
        return [
            'Behavior'      => ['light' => 'bg-label-primary', 'dark' => 'bg-primary'],
            'Appreciation'  => ['light' => 'bg-label-success', 'dark' => 'bg-success'],
            'Leadership'    => ['light' => 'bg-label-info', 'dark' => 'bg-info'],
            'Loyalty'       => ['light' => 'bg-label-warning', 'dark' => 'bg-warning'],
            'Dedication'    => ['light' => 'bg-label-danger', 'dark' => 'bg-danger'],
            'Teamwork'      => ['light' => 'bg-label-secondary', 'dark' => 'bg-secondary'],
            'Innovation'    => ['light' => 'bg-label-dark', 'dark' => 'bg-dark'],
        ];
    }

    /**
     * Get the category badge color class based on category name.
     */
    public function getCategoryBadgeColorAttribute(): string
    {
        return $this->categoryColors()[$this->category]['dark'] ?? 'bg-primary';
    }

    public function getCategoryBadgeColorLightAttribute(): string
    {
        return $this->categoryColors()[$this->category]['light'] ?? 'bg-label-primary';
    }

    /**
     * Get the category icon based on category name.
     */
    public function getCategoryIconAttribute(): string
    {
        $categoryIcons = [
            'Behavior' => 'ti ti-user-check',
            'Appreciation' => 'ti ti-heart',
            'Leadership' => 'ti ti-crown',
            'Loyalty' => 'ti ti-shield-check',
            'Dedication' => 'ti ti-target',
            'Teamwork' => 'ti ti-users',
            'Innovation' => 'ti ti-bulb'
        ];

        return $categoryIcons[$this->category] ?? 'ti ti-award';
    }

    /**
     * Get the score badge color class based on score percentage.
     */
    public function getScoreBadgeColorAttribute(): string
    {
        $maxScore = config('recognition.marks.max');
        $percentage = ($this->total_mark / $maxScore) * 100;

        if ($percentage >= 90) {
            return 'bg-label-success';
        } elseif ($percentage >= 80) {
            return 'bg-label-info';
        } elseif ($percentage >= 70) {
            return 'bg-label-warning';
        } elseif ($percentage >= 60) {
            return 'bg-label-primary';
        } else {
            return 'bg-label-danger';
        }
    }

    /**
     * Get the score percentage.
     */
    public function getScorePercentageAttribute(): float
    {
        $maxScore = config('recognition.marks.max');
        return round(($this->total_mark / $maxScore) * 100, 1);
    }

    /**
     * Get formatted score display (e.g., "850/1000").
     */
    public function getFormattedScoreAttribute(): string
    {
        return $this->total_mark . '/' . config('recognition.marks.max');
    }
}
