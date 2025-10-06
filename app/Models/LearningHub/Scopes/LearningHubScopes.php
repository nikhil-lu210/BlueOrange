<?php

namespace App\Models\LearningHub\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\User;

trait LearningHubScopes
{
    /**
     * Scope to filter by creator
     */
    public function scopeByCreator(Builder $query, $creatorId): Builder
    {
        return $creatorId ? $query->where('creator_id', $creatorId) : $query;
    }

    /**
     * Scope to filter by month and year
     */
    public function scopeByMonthYear(Builder $query, $monthYear): Builder
    {
        if (!$monthYear) return $query;

        $date = Carbon::parse($monthYear);
        return $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
    }

    /**
     * Scope to get topics visible to a specific user
     */
    public function scopeVisibleTo(Builder $query, $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('creator_id', $userId)
              ->orWhereRaw('JSON_CONTAINS(recipients, ?)', [$userId])
              ->orWhereNull('recipients');
        });
    }

    /**
     * Scope to get topics created by user's interactions
     */
    public function scopeByUserInteractions(Builder $query, $userId): Builder
    {
        $user = User::find($userId);
        $interactingUserIds = $user->user_interactions->pluck('id')->toArray();
        
        return $query->whereIn('creator_id', $interactingUserIds);
    }

    /**
     * Scope to get topics visible to authenticated user
     */
    public function scopeForAuthenticatedUser(Builder $query): Builder
    {
        $userId = auth()->id();
        return $query->visibleTo($userId);
    }

    /**
     * Scope to order by latest
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope to include creator with employee and media
     */
    public function scopeWithCreatorDetails(Builder $query): Builder
    {
        return $query->with(['creator.employee', 'creator.media', 'creator.roles']);
    }
}
