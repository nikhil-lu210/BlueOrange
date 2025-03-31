<?php

namespace App\Models\Religion\Mutators;

use Illuminate\Support\Str;

trait ReligionMutators
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
