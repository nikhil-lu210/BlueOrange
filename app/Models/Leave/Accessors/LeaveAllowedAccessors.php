<?php

namespace App\Models\Leave\Accessors;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait LeaveAllowedAccessors
{
    /**
     * Accessor for earned_leave.
     * Converts the stored string (hh:mm:ss) into a CarbonInterval object.
     *
     * @param string $value
     * @return CarbonInterval
     */
    public function getEarnedLeaveAttribute(string $value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for casual_leave.
     * Converts the stored string (hh:mm:ss) into a CarbonInterval object.
     *
     * @param string $value
     * @return CarbonInterval
     */
    public function getCasualLeaveAttribute(string $value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for sick_leave.
     * Converts the stored string (hh:mm:ss) into a CarbonInterval object.
     *
     * @param string $value
     * @return CarbonInterval
     */
    public function getSickLeaveAttribute(string $value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for implemented_from.
     * Converts the stored string (mm-dd) into a Carbon date object.
     *
     * @param string $value
     * @return Carbon
     */
    public function getImplementedFromAttribute(string $value): Carbon
    {
        return Carbon::createFromFormat('m-d', $value);
    }

    /**
     * Accessor for implemented_to.
     * Converts the stored string (mm-dd) into a Carbon date object.
     *
     * @param string $value
     * @return Carbon
     */
    public function getImplementedToAttribute(string $value): Carbon
    {
        return Carbon::createFromFormat('m-d', $value);
    }
}
