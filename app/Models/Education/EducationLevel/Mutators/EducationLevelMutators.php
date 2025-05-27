<?php

namespace App\Models\Education\EducationLevel\Mutators;

use Illuminate\Support\Str;

trait EducationLevelMutators
{
    /**
     * Mutator to set the slug automatically from the title.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
