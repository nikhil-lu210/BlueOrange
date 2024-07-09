<?php

namespace App\Models\User\Traits;

use App\Models\Task\Task;
use App\Models\Salary\Salary;
use App\Models\Attendance\Attendance;
use App\Models\Announcement\Announcement;
use App\Models\EmployeeShift\EmployeeShift;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\Announcement\AnnouncementComment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Relations
{    
    /**
     * Get the employee_shifts associated with the user.
     */
    public function employee_shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Get currently active employee_shift
     */
    public function getCurrentShiftAttribute()
    {
        return $this->employee_shifts()->where('status', 'active')->latest()->first();
    }
    
    /**
     * Get the salaries associated with the user.
     */
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Get currently active salary
     */
    public function getCurrentSalaryAttribute()
    {
        return $this->salaries()->where('status', 'active')->latest()->first();
    }

    /**
     * Get the monthly_salaries associated with the user.
     */
    public function monthly_salaries(): HasMany
    {
        return $this->hasMany(MonthlySalary::class);
    }

    /**
     * Get the attendances associated with the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the announcements associated with the user.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'announcer_id');
    }

    /**
     * Get the announcement_comments associated with the user.
     */
    public function announcement_comments(): HasMany
    {
        return $this->hasMany(AnnouncementComment::class, 'commenter_id');
    }

    /**
     * Get the created_tasks associated with the user.
     */
    public function created_tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }


    /**
     * Get the tasks associated with the user.
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withPivot('progress')->withTimestamps();
    }
}