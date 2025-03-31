<?php

namespace App\Models\Religion\Relations;

use App\Models\User;
use App\Models\User\Employee\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait ReligionRelations
{
    /**
     * Get the employees associated with the religion.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Define the relationship to get users through the employees
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Employee::class, 'religion_id', 'id', 'id', 'user_id');
    }
}
