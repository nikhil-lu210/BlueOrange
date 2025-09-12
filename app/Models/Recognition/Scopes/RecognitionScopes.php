<?php

namespace App\Models\Recognition\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

trait RecognitionScopes
{
    /**
     * Scope a query to only include recognitions for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include recognitions by a specific recognizer.
     */
    public function scopeByRecognizer(Builder $query, int $recognizerId): Builder
    {
        return $query->where('recognizer_id', $recognizerId);
    }

    /**
     * Scope a query to only include recognitions of a specific category.
     */
    public function scopeOfCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include recognitions within a score range.
     */
    public function scopeWithScoreRange(Builder $query, int $minScore, int $maxScore): Builder
    {
        return $query->whereBetween('total_mark', [$minScore, $maxScore]);
    }

    /**
     * Scope a query to only include recognitions created within a date range.
     */
    public function scopeCreatedBetween(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include recognitions created this month.
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include recognitions created this year.
     */
    public function scopeThisYear(Builder $query): Builder
    {
        return $query->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include recognitions with high scores.
     */
    public function scopeHighScore(Builder $query): Builder
    {
        $threshold = config('recognition.marks.max') * 0.8; // 80% of max score
        return $query->where('total_mark', '>=', $threshold);
    }

    /**
     * Scope a query to only include recognitions with low scores.
     */
    public function scopeLowScore(Builder $query): Builder
    {
        $threshold = config('recognition.marks.min') * 1.2; // 20% above min score
        return $query->where('total_mark', '<=', $threshold);
    }

    /**
     * Scope a query to order by score descending.
     */
    public function scopeOrderByScore(Builder $query): Builder
    {
        return $query->orderBy('total_mark', 'desc');
    }

    /**
     * Scope a query to order by creation date descending.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to include only recent recognitions.
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to search recognitions by comment content.
     */
    public function scopeSearchComment(Builder $query, string $search): Builder
    {
        return $query->where('comment', 'like', "%{$search}%");
    }

    /**
     * Scope a query to include recognitions for team members.
     */
    public function scopeForTeamMembers(Builder $query, array $teamMemberIds): Builder
    {
        return $query->whereIn('user_id', $teamMemberIds);
    }
}
