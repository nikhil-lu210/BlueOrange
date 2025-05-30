<?php

namespace App\Models\User\Relations;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\Vault\Vault;
use App\Models\Salary\Salary;
use App\Models\Comment\Comment;
use App\Models\Ticket\ItTicket;
use App\Models\Religion\Religion;
use App\Models\Shortcut\Shortcut;
use App\Models\User\LoginHistory;
use App\Models\Leave\LeaveAllowed;
use App\Models\Leave\LeaveHistory;
use App\Models\Leave\LeaveAvailable;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use App\Models\Chatting\ChattingGroup;
use App\Models\User\Employee\Employee;
use App\Models\Announcement\Announcement;
use App\Models\EmployeeShift\EmployeeShift;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\Education\Institute\Institute;
use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Models\Announcement\AnnouncementComment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Education\EducationLevel\EducationLevel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait UserRelations
{
    /**
     * Get the employee associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    // Define the one-to-many relationship with Religion through Employee
    public function religion(): HasOneThrough
    {
        return $this->hasOneThrough(Religion::class, Employee::class, 'user_id', 'id', 'id', 'religion_id');
    }

    // Define the one-to-many relationship with institute through Employee
    public function institute(): HasOneThrough
    {
        return $this->hasOneThrough(Institute::class, Employee::class, 'user_id', 'id', 'id', 'institute_id');
    }

    // Define the one-to-many relationship with education_level through Employee
    public function education_level(): HasOneThrough
    {
        return $this->hasOneThrough(EducationLevel::class, Employee::class, 'user_id', 'id', 'id', 'education_level_id');
    }

    /**
     * Get the employee_shifts associated with the user.
     */
    public function employee_shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }


    /**
     * Get the login_logout_histories associated with the user.
     */
    public function login_logout_histories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Get the chatting_groups associated with the user.
     */
    public function chatting_groups(): BelongsToMany
    {
        return $this->belongsToMany(ChattingGroup::class, 'chatting_group_user')->withTimestamps();
    }

    /**
     * Get the shortcuts associated with the user.
     */
    public function shortcuts(): HasMany
    {
        return $this->hasMany(Shortcut::class);
    }

    /**
     * Get the leave_alloweds associated with the user.
     */
    public function leave_alloweds(): HasMany
    {
        return $this->hasMany(LeaveAllowed::class);
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
     * Get the scanner_clockins associated with the user.
     */
    public function scanner_clockins(): HasMany
    {
        return $this->hasMany(Attendance::class, 'clockin_scanner_id');
    }

    /**
     * Get the scanner_clockouts associated with the user.
     */
    public function scanner_clockouts(): HasMany
    {
        return $this->hasMany(Attendance::class, 'clockout_scanner_id');
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
     * Get the comments associated with the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
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
