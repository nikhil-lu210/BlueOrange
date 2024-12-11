<?php

namespace App\Models\User\Traits;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\Salary\Salary;
use App\Models\Task\TaskComment;
use App\Models\Shortcut\Shortcut;
use App\Models\User\LoginHistory;
use App\Models\Attendance\Attendance;
use App\Models\Announcement\Announcement;
use App\Models\EmployeeShift\EmployeeShift;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Models\Announcement\AnnouncementComment;
use App\Models\DailyBreak\DailyBreak;
use App\Models\Leave\LeaveAllowed;
use App\Models\Leave\LeaveAvailable;
use App\Models\Leave\LeaveHistory;
use App\Models\Ticket\ItTicket;
use App\Models\User\Employee\Employee;
use App\Models\Vault\Vault;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Relations
{    
    /**
     * Get the employee associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
    
    
    /**
     * Get the login_logout_histories associated with the user.
     */
    public function login_logout_histories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }
    
    /**
     * Get the shortcuts associated with the user.
     */
    public function shortcuts(): HasMany
    {
        return $this->hasMany(Shortcut::class);
    }
    
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
     * Get the leave_alloweds associated with the user.
     */
    public function leave_alloweds(): HasMany
    {
        return $this->hasMany(LeaveAllowed::class);
    }

    /**
     * Get the active allowed leave
     */
    public function getAllowedLeaveAttribute()
    {
        // Retrieve the first active leave_alloweds entry for the user
        $activeLeave = $this->leave_alloweds()->where('is_active', true)->first();

        // Check if an active leave entry exists
        return $activeLeave ?: null; // Return the active leave entry or null if not found
    }


    
    /**
     * Get the leave_availables associated with the user.
     */
    public function leave_availables(): HasMany
    {
        return $this->hasMany(LeaveAvailable::class);
    }

    /**
     * Accessor to get available leaves for a given year or the current year.
     * Falls back to allowed leaves if no available leaves are found.
     *
     * @param int|null $year
     * @return LeaveAvailable|LeaveAllowed|null
     */
    public function available_leaves($year = null)
    {
        // If no year is provided, use the current year
        $year = $year ?: now()->year;

        // Retrieve the leave available for the specified year
        $leaveAvailable = $this->leave_availables()->where('for_year', $year)->first();

        // If no leave available is found, fetch the allowed leaves
        if (!$leaveAvailable) {
            return $this->getAllowedLeaveAttribute();
        }

        return $leaveAvailable;
    }

    
    /**
     * Get the leave_histories associated with the user.
     */
    public function leave_histories(): HasMany
    {
        return $this->hasMany(LeaveHistory::class);
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
     * Get the paid_salaries associated with the user.
     * the salaries has been paid by this user
     */
    public function paid_salaries(): HasMany
    {
        return $this->hasMany(MonthlySalary::class, 'paid_by');
    }

    /**
     * Get the attendances associated with the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the qr_clockins associated with the user.
     */
    public function qr_clockins(): HasMany
    {
        return $this->hasMany(Attendance::class, 'qr_clockin_scanner_id');
    }

    /**
     * Get the qr_clockouts associated with the user.
     */
    public function qr_clockouts(): HasMany
    {
        return $this->hasMany(Attendance::class, 'qr_clockout_scanner_id');
    }

    /**
     * Get the daily_breaks associated with the user.
     */
    public function daily_breaks(): HasMany
    {
        return $this->hasMany(DailyBreak::class);
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


    /**
     * Get the vaults associated with the user.
     */
    public function vaults(): BelongsToMany
    {
        return $this->belongsToMany(Vault::class)->withTimestamps();
    }

    /**
     * Get the it_tickets associated with the user.
     */
    public function it_tickets(): HasMany
    {
        return $this->hasMany(ItTicket::class, 'creator_id');
    }

    /**
     * Get the it_ticket_solves associated with the user.
     */
    public function it_ticket_solves(): HasMany
    {
        return $this->hasMany(ItTicket::class, 'solved_by');
    }

    /**
     * Get the task_comments associated with the user.
     */
    public function task_comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'user_id');
    }

    /**
     * Get the tl_employees associated with the user (team_leader).
     */
    public function tl_employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'employee_team_leader', 'team_leader_id', 'employee_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Get the employee_team_leaders associated with the user (employee).
     */
    public function employee_team_leaders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'employee_team_leader', 'employee_id', 'team_leader_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Get the daily_work_updates associated with the user.
     */
    public function daily_work_updates(): HasMany
    {
        return $this->hasMany(DailyWorkUpdate::class, 'user_id');
    }

    /**
     * Get the tl_employees_daily_work_updates associated with the user.
     */
    public function tl_employees_daily_work_updates(): HasMany
    {
        return $this->hasMany(DailyWorkUpdate::class, 'team_leader_id');
    }

    /**
     * Users that this user is interacting with
     * Get the interacted_users associated with the user.
     */
    public function interacted_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interactions', 'user_id', 'interacted_user_id')
                    ->withTimestamps();
    }

    /**
     * Users that are interacting with this user
     * Get the interacting_users associated with the user.
     */
    public function interacting_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interactions', 'interacted_user_id', 'user_id')
                    ->withTimestamps();
    }
}