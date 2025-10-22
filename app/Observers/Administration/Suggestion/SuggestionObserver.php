<?php

namespace App\Observers\Administration\Suggestion;

use App\Models\Suggestion\Suggestion;

class SuggestionObserver
{
    /**
     * Handle the Suggestion "created" event.
     */
    public function created(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "updated" event.
     */
    public function updated(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "deleted" event.
     */
    public function deleted(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "restored" event.
     */
    public function restored(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "force deleted" event.
     */
    public function forceDeleted(Suggestion $suggestion): void
    {
        //
    }
}
