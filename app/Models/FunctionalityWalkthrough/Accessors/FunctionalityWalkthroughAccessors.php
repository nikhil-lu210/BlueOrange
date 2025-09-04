<?php

namespace App\Models\FunctionalityWalkthrough\Accessors;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

trait FunctionalityWalkthroughAccessors
{
    /**
     * Get the assigned roles as a collection
     */
    public function getAssignedRolesAttribute($value): Collection
    {
        if (is_null($value)) {
            return collect();
        }

        $roleIds = is_array($value) ? $value : json_decode($value, true);

        if (empty($roleIds)) {
            return collect();
        }

        return Role::whereIn('id', $roleIds)->get();
    }

    /**
     * Get the read by users as a collection
     */
    public function getReadByAtAttribute($value): Collection
    {
        if (is_null($value)) {
            return collect();
        }

        $reads = is_array($value) ? $value : json_decode($value, true);

        if (empty($reads)) {
            return collect();
        }

        return collect($reads);
    }

    /**
     * Get the steps count
     */
    public function getStepsCountAttribute(): int
    {
        return $this->steps->count();
    }


    /**
     * Check if walkthrough has steps
     */
    public function getHasStepsAttribute(): bool
    {
        return $this->steps_count > 0;
    }
}
