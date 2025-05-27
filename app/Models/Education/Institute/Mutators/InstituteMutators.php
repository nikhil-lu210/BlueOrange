<?php

namespace App\Models\Education\Institute\Mutators;

use Illuminate\Support\Str;

trait InstituteMutators
{
    /**
     * Mutator to set the slug automatically from the name.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
