<?php

namespace App\Observers\Administration\Translation;

use App\Models\Translation\Translation;

class TranslationObserver
{
    /**
     * Handle the Translation "created" event.
     */
    public function created(Translation $translation): void
    {
        //
    }

    /**
     * Handle the Translation "updated" event.
     */
    public function updated(Translation $translation): void
    {
        //
    }

    /**
     * Handle the Translation "deleted" event.
     */
    public function deleted(Translation $translation): void
    {
        //
    }

    /**
     * Handle the Translation "restored" event.
     */
    public function restored(Translation $translation): void
    {
        //
    }

    /**
     * Handle the Translation "force deleted" event.
     */
    public function forceDeleted(Translation $translation): void
    {
        //
    }
}
