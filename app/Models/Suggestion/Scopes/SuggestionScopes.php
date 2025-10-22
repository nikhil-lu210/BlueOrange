<?php

namespace App\Models\Suggestion\Scopes;
use Illuminate\Database\Eloquent\Builder;

trait SuggestionScopes
{

    /**
     * Scope to filter by user ID
     */
    public function scopeFilterByUser(Builder $query, $userId = null): Builder
    {
        return $query->when($userId, fn($q) => $q->where('user_id', $userId));
    }

    /**
     * Scope to filter by type
     */
    public function scopeFilterByType(Builder $query, $type = null): Builder
    {
        return $query->when($type, fn($q) => $q->where('type', $type));
    }

    /**
     * Scope to filter by module
     */
    public function scopeFilterByModule(Builder $query, $module = null): Builder
    {
        return $query->when($module, fn($q) => $q->where('module', $module));
    }

    /**
     * Optional — if you want to chain all filters in one go
     */
    public function scopeApplyFilters(Builder $query, $filters = []): Builder
    {
        return $query
            ->filterByUser($filters['user_id'] ?? null)
            ->filterByType($filters['type'] ?? null)
            ->filterByModule($filters['module'] ?? null);
    }
}