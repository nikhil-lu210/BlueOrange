<?php

namespace App\Observers\Administration\WorkSchedule;

use App\Models\WorkSchedule\WorkSchedule;

class WorkScheduleObserver
{
    /**
     * Handle the WorkSchedule "created" event.
     */
    public function created(WorkSchedule $workSchedule): void
    {
        //
    }

    /**
     * Handle the WorkSchedule "updated" event.
     */
    public function updated(WorkSchedule $workSchedule): void
    {
        //
    }

    /**
     * Handle the WorkSchedule "deleted" event.
     */
    public function deleted(WorkSchedule $workSchedule): void
    {
        //
    }

    /**
     * Handle the WorkSchedule "restored" event.
     */
    public function restored(WorkSchedule $workSchedule): void
    {
        //
    }

    /**
     * Handle the WorkSchedule "force deleted" event.
     */
    public function forceDeleted(WorkSchedule $workSchedule): void
    {
        //
    }
}
