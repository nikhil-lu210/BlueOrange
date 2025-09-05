<?php

namespace App\Observers\Administration\WorkScheduleItem;

use App\Models\WorkScheduleItem\WorkScheduleItem;

class WorkScheduleItemObserver
{
    /**
     * Handle the WorkScheduleItem "created" event.
     */
    public function created(WorkScheduleItem $workScheduleItem): void
    {
        //
    }

    /**
     * Handle the WorkScheduleItem "updated" event.
     */
    public function updated(WorkScheduleItem $workScheduleItem): void
    {
        //
    }

    /**
     * Handle the WorkScheduleItem "deleted" event.
     */
    public function deleted(WorkScheduleItem $workScheduleItem): void
    {
        //
    }

    /**
     * Handle the WorkScheduleItem "restored" event.
     */
    public function restored(WorkScheduleItem $workScheduleItem): void
    {
        //
    }

    /**
     * Handle the WorkScheduleItem "force deleted" event.
     */
    public function forceDeleted(WorkScheduleItem $workScheduleItem): void
    {
        //
    }
}
