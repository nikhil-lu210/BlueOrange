<?php

namespace App\Models\FunctionalityWalkthrough\Relations;

trait FunctionalityWalkthroughRelations
{
    /**
     * Get the user that created the walkthrough.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    /**
     * Get the steps for the walkthrough.
     */
    public function steps()
    {
        return $this->hasMany(\App\Models\FunctionalityWalkthrough\FunctionalityWalkthroughStep::class, 'walkthrough_id')->orderBy('step_order');
    }

    /**
     * Get the files for the walkthrough.
     */
    public function files()
    {
        return $this->morphMany(\App\Models\FileMedia\FileMedia::class, 'fileable');
    }
}
