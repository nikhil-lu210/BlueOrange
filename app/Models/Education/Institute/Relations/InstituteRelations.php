<?php

namespace App\Models\Education\Institute\Relations;

use App\Models\User;
use App\Models\User\Employee\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait InstituteRelations
{
    /**
     * Get the employees associated with the institute.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Define the relationship to get users through the employees
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Employee::class, 'institute_id', 'id', 'id', 'user_id');
    }
}
