<?php

namespace App\Models\User\Mutators;

trait UserMutators
{
    /**
     * Set the user's first_name with proper capitalization.
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }
    
    /**
     * Set the user's first_name with proper capitalization.
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }
}
