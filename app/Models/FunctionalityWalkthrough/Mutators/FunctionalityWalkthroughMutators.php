<?php

namespace App\Models\FunctionalityWalkthrough\Mutators;

trait FunctionalityWalkthroughMutators
{
    /**
     * Set the assigned_roles attribute
     */
    public function setAssignedRolesAttribute($value)
    {
        if (is_null($value) || empty($value)) {
            $this->attributes['assigned_roles'] = null;
        } else {
            $this->attributes['assigned_roles'] = json_encode($value);
        }
    }

    /**
     * Set the read_by_at attribute
     */
    public function setReadByAtAttribute($value)
    {
        if (is_null($value) || empty($value)) {
            $this->attributes['read_by_at'] = null;
        } else {
            $this->attributes['read_by_at'] = json_encode($value);
        }
    }

    /**
     * Set the title attribute with proper formatting
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst(trim($value));
    }
}
