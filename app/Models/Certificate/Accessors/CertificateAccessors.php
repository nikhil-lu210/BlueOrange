<?php

namespace App\Models\Certificate\Accessors;

trait CertificateAccessors
{
    /**
     * Get formatted salary
     */
    public function getFormattedSalaryAttribute()
    {
        return $this->salary ? number_format($this->salary, 2) : null;
    }

    /**
     * Get formatted issue date
     */
    public function getFormattedIssueDateAttribute()
    {
        return $this->issue_date ? $this->issue_date->format('F j, Y') : null;
    }

    /**
     * Get formatted joining date from employee
     */
    public function getFormattedJoiningDateAttribute()
    {
        return $this->user && $this->user->employee && $this->user->employee->joining_date
            ? \Carbon\Carbon::parse($this->user->employee->joining_date)->format('F j, Y')
            : null;
    }

    /**
     * Get formatted resignation date
     */
    public function getFormattedResignationDateAttribute()
    {
        return $this->resignation_date ? $this->resignation_date->format('F j, Y') : null;
    }

    /**
     * Get formatted resign application date
     */
    public function getFormattedResignApplicationDateAttribute()
    {
        return $this->resign_application_date ? $this->resign_application_date->format('F j, Y') : null;
    }

    /**
     * Get formatted resignation approval date
     */
    public function getFormattedResignationApprovalDateAttribute()
    {
        return $this->resignation_approval_date ? $this->resignation_approval_date->format('F j, Y') : null;
    }

    /**
     * Get formatted release date
     */
    public function getFormattedReleaseDateAttribute()
    {
        return $this->release_date ? $this->release_date->format('F j, Y') : null;
    }

    /**
     * Get formatted leave starts from date
     */
    public function getFormattedLeaveStartsFromAttribute()
    {
        return $this->leave_starts_from ? $this->leave_starts_from->format('F j, Y') : null;
    }

    /**
     * Get formatted leave ends on date
     */
    public function getFormattedLeaveEndsOnAttribute()
    {
        return $this->leave_ends_on ? $this->leave_ends_on->format('F j, Y') : null;
    }
}
