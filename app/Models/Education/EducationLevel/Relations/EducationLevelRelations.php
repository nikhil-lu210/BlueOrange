<?php

namespace App\Models\Education\EducationLevel\Relations;

use App\Models\User;
use App\Models\User\Employee\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait EducationLevelRelations
{
    /**
     * Get the employees associated with the education_level.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Define the relationship to get users through the employees
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Employee::class, 'education_level_id', 'id', 'id', 'user_id');
    }
}
