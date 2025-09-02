<?php

namespace App\Models\LearningHub\Accessors;

use App\Models\User;
use Illuminate\Support\Collection;

trait LearningHubAccessors
{
    /**
     * Always return recipients as a collection of User models with employee relation.
     */
    public function getRecipientsAttribute($value): Collection
    {
        // Convert JSON or array to clean array of IDs
        $ids = is_string($value) ? json_decode($value, true) : $value;

        $ids = collect($ids ?? [])->filter()->map(fn($id) => (int) $id)->all();

        // Fetch users with employee relation
        return User::with('employee')->whereIn('id', $ids)->get();
    }

    /**
     * Always return read_by_at as a collection of objects,
     * where read_by is a User model with employee relation.
     */
    public function getReadByAtAttribute($value): Collection
    {
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        return collect($decoded ?? [])->map(function ($item) {
            $user = User::with('employee')->find($item['read_by']);

            return (object) [
                'read_by' => $user, // full User model instead of just ID
                'read_at' => $item['read_at'],
            ];
        });
    }
}
