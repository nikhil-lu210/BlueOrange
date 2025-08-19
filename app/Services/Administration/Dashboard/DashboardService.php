<?php

namespace App\Services\Administration\Dashboard;

use App\Models\User;
use App\Enums\BloodGroup;
use Illuminate\Support\Carbon;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Collection;
use App\Models\Attendance\Attendance;
use App\Models\Recognition\Recognition;
use App\Models\Education\Institute\Institute;
use App\Models\Education\EducationLevel\EducationLevel;
use App\Services\Administration\Attendance\AttendanceService;
use App\Services\Administration\Recognition\RecognitionService;

class DashboardService {
    protected $attendanceService;
    protected $recognitionService;

    /**
     * Create a new service instance.
     */
    public function __construct(AttendanceService $attendanceService, RecognitionService $recognitionService)
    {
        $this->attendanceService = $attendanceService;
        $this->recognitionService = $recognitionService;
    }

    /**
     * Get the current authenticated user with employee relationship.
     */
    public function getCurrentUser(): User
    {
        return User::with(['employee'])->whereId(auth()->user()->id)->firstOrFail();
    }

    /**
     * Get attendance statistics for the user.
     */
    public function getAttendanceStatistics(User $user): array
    {
        return [
            'totalWorkedDays' => $this->attendanceService->calculateTotalWorkedDays($user),
            'totalRegularWork' => $this->attendanceService->calculateTotalWork($user, 'Regular'),
            'totalOvertimeWork' => $this->attendanceService->calculateTotalWork($user, 'Overtime'),
            'totalRegularWorkingHour' => $this->attendanceService->totalWorkingHour($user, 'Regular'),
            'totalOvertimeWorkingHour' => $this->attendanceService->totalWorkingHour($user, 'Overtime'),
        ];
    }

    /**
     * Get the active attendance for the user.
     */
    public function getActiveAttendance(User $user)
    {
        return Attendance::select(['id', 'user_id', 'type', 'clock_in', 'clock_out'])
            ->whereUserId($user->id)
            ->whereNull('clock_out')
            ->latest()
            ->first();
    }

    /**
     * Get the current month's attendances for the user.
     */
    public function getCurrentMonthAttendances(User $user): Collection
    {
        return Attendance::whereUserId($user->id)
            ->whereBetween('clock_in_date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ])
            ->orderByDesc('clock_in_date')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get users who are currently working.
     */
    public function getCurrentlyWorkingUsers(): Collection
    {
        // First, get all users who have active attendances
        $usersWithActiveAttendances = User::with(['employee', 'media'])
            ->where('status', 'Active')
            ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
            ->whereHas('attendances', function($subQuery) {
                // Users who are currently working (clocked in but not clocked out)
                $subQuery->whereNull('clock_out')
                        ->whereNotNull('clock_in');
            })
            ->get();

        // Then, for each user, load their current attendance
        $usersWithActiveAttendances->each(function($user) {
            $currentAttendance = Attendance::where('user_id', $user->id)
                ->whereNull('clock_out')
                ->whereNotNull('clock_in')
                ->orderBy('clock_in', 'desc')
                ->first();

            // Set the attendance as a collection to maintain consistency with the original approach
            $user->setRelation('attendances', collect($currentAttendance ? [$currentAttendance] : []));
        });

        // Filter out users who don't have any current attendance (safety check)
        return $usersWithActiveAttendances->filter(function($user) {
            return $user->attendances->isNotEmpty();
        });
    }

    /**
     * Get the current active attendance for a user.
     * This is a helper method to safely get the current attendance.
     */
    public function getCurrentAttendanceForUser(User $user)
    {
        // If attendances are already loaded, use them
        if ($user->relationLoaded('attendances') && $user->attendances->isNotEmpty()) {
            return $user->attendances->first();
        }

        // Otherwise, query for the current attendance
        return Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->whereNotNull('clock_in')
            ->orderBy('clock_in', 'desc')
            ->first();
    }

    /**
     * Get users who are on leave today.
     */
    public function getUsersOnLeaveToday(): Collection
    {
        $today = Carbon::today();

        return User::with(['employee', 'media'])
            ->where('status', 'Active')
            ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
            ->whereHas('leave_histories', function($query) use ($today) {
                $query->whereDate('date', $today)
                      ->whereIn('status', ['Pending', 'Approved']);
            })
            ->get();
    }

    /**
     * Get users who are absent today.
     */
    public function getAbsentUsers(): Collection
    {
        $today = Carbon::today();
        $currentTime = Carbon::now();

        // Get IDs of users who are currently working to exclude them from absent list
        $currentlyWorkingUserIds = $this->getCurrentlyWorkingUsers()->pluck('id')->toArray();

        // First, get all users with active shifts
        $usersWithActiveShifts = User::with(['employee', 'media', 'employee_shifts'])
            ->where('status', 'Active')
            ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
            ->whereNotIn('id', $currentlyWorkingUserIds) // Exclude users who are currently working
            ->whereHas('employee_shifts', function($query) {
                $query->where('status', 'Active');
            })
            ->get();

        // Filter users whose shift has started but have no attendance today
        return $usersWithActiveShifts->filter(function($user) use ($today, $currentTime) {
            // Get the active shift for this user
            $activeShift = $user->employee_shifts()
                ->where('status', 'Active')
                ->latest('created_at')
                ->first();

            // Check if user has an active shift
            if (!$activeShift) {
                return false;
            }

            // Parse shift start and end times
            $shiftStartTime = Carbon::parse($today->format('Y-m-d') . ' ' . $activeShift->start_time);
            $shiftEndTime = Carbon::parse($today->format('Y-m-d') . ' ' . $activeShift->end_time);

            // Handle overnight shifts (end time is earlier than start time, meaning it's the next day)
            if ($shiftEndTime->lt($shiftStartTime)) {
                $shiftEndTime->addDay();
            }

            // For overnight shifts that are currently active (after midnight)
            $yesterdayDate = $today->copy()->subDay();
            $yesterdayShiftStart = Carbon::parse($yesterdayDate->format('Y-m-d') . ' ' . $activeShift->start_time);
            $yesterdayShiftEnd = Carbon::parse($yesterdayDate->format('Y-m-d') . ' ' . $activeShift->end_time);

            if ($yesterdayShiftEnd->lt($yesterdayShiftStart)) {
                $yesterdayShiftEnd->addDay(); // This would make it today
            }

            // Check if current time is within yesterday's overnight shift that extends to today
            $isInOvernightShift = $currentTime->gte($yesterdayShiftStart) && $currentTime->lte($yesterdayShiftEnd);

            // For regular shifts, check if the shift has started today
            $hasShiftStartedToday = $currentTime->gte($shiftStartTime);

            // If neither condition is met, the user shouldn't be considered absent
            if (!$hasShiftStartedToday && !$isInOvernightShift) {
                return false;
            }

            // Check if user has attendance record for today or an active attendance from yesterday (for overnight shifts)
            $hasAttendanceToday = Attendance::where('user_id', $user->id)
                                      ->whereDate('clock_in_date', $today)
                                      ->exists();

            $hasActiveOvernightAttendance = Attendance::where('user_id', $user->id)
                                      ->whereDate('clock_in_date', $yesterdayDate)
                                      ->whereNull('clock_out')
                                      ->exists();

            $hasAttendance = $hasAttendanceToday || $hasActiveOvernightAttendance;

            // Check if user is on leave today
            $isOnLeave = LeaveHistory::where('user_id', $user->id)
                                    ->whereDate('date', $today)
                                    ->whereIn('status', ['Pending', 'Approved'])
                                    ->exists();

            // User is absent if shift has started (or is in overnight shift), but no attendance and not on leave
            return !$hasAttendance && !$isOnLeave;
        });
    }

    /**
     * Check if the employee info update modal should be shown.
     */
    public function shouldShowEmployeeInfoUpdateModal(User $user): bool
    {
        $employee = $user->employee;

        return collect([
            $employee->blood_group,
            $employee->father_name,
            $employee->mother_name,
            $employee->institute_id,
            $employee->education_level_id,
            $employee->passing_year,
            // Add more fields as needed
        ])->contains(function ($value) {
            return is_invalid_employee_value($value);
        });
    }

    /**
     * Get grouped blood groups for dropdown.
     */
    public function getGroupedBloodGroups(): array
    {
        return [
            'Standard (ABO + Rh)' => [
                BloodGroup::A_POSITIVE,
                BloodGroup::A_NEGATIVE,
                BloodGroup::B_POSITIVE,
                BloodGroup::B_NEGATIVE,
                BloodGroup::AB_POSITIVE,
                BloodGroup::AB_NEGATIVE,
                BloodGroup::O_POSITIVE,
                BloodGroup::O_NEGATIVE,
            ],
            'Rare Types' => [
                BloodGroup::RH_NULL,
                BloodGroup::BOMBAY,
            ],
            'Other Systems' => [
                BloodGroup::KELL_POS,
                BloodGroup::KELL_NEG,
                BloodGroup::DUFFY_A,
                BloodGroup::DUFFY_B,
                BloodGroup::KIDD_A,
                BloodGroup::KIDD_B,
                BloodGroup::MNS_MN,
                BloodGroup::MNS_SS,
                BloodGroup::LUTHERAN_A,
                BloodGroup::LUTHERAN_B,
                BloodGroup::DIEGO_A,
                BloodGroup::DIEGO_B,
                BloodGroup::LEWIS_A,
                BloodGroup::LEWIS_B,
                BloodGroup::P1,
                BloodGroup::P_SMALL,
            ],
        ];
    }

    /**
     * Get a random birthday wish.
     */
    public function getRandomBirthdayWish(): string
    {
        // Predefined array of birthday wishes
        $birthdayWishes = [
            "May your birthday be the start of a year filled with good luck, good health, and much happiness.",
            "Enjoy your special day with all the people you love.",
            "Wishing you a day that's as special as you are!",
            "This day is as special as you are. Your contributions to our team are invaluable, and your positive energy always brightens our day. Thank you for your hard work and dedication. May this year bring you even greater success and happiness. Enjoy your special day with loved ones and make some unforgettable memories. Cheers to another year of growth and achievement!",
            "May your birthday be a day filled with laughter, love, and all the things that make you happy. May the year ahead be filled with joy, success, and endless possibilities.",
            "Happy birthday to a truly exceptional person. You inspire us all with your kindness, intelligence, and unwavering spirit. May this day be as special as you are.",
            "As you celebrate another year of life, we want to express our sincere gratitude for your friendship and support. You make a positive impact on everyone around you, and we're lucky to have you in our lives. Happy birthday!",
            "May your birthday be a day filled with sunshine, laughter, and love. May the year ahead be filled with new adventures, exciting opportunities, and endless joy. Happy birthday!",
            "Happy birthday to a remarkable individual. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!",
            "Wishing you a birthday that is as bright and beautiful as you are. May your day be filled with joy, laughter, and love from all the people who care about you. Happy birthday!",
            "As you celebrate another year of life, we want to express our gratitude for your friendship and your contributions to our team. You are a valued member of our community, and we are lucky to have you. Happy birthday!",
            "May your birthday be a day filled with peace, love, and happiness. May the year ahead be filled with good health, prosperity, and success. Happy birthday!",
            "Happy birthday to a truly amazing person. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!"
        ];

        // Return a random birthday wish
        return $birthdayWishes[array_rand($birthdayWishes)];
    }

    /**
     * Get all institutes for dropdown.
     */
    public function getAllInstitutes(): Collection
    {
        return Institute::orderBy('name')->get();
    }

    /**
     * Get all education levels for dropdown.
     */
    public function getAllEducationLevels(): Collection
    {
        return EducationLevel::orderBy('title')->get();
    }

    /**
     * Check if the team leader should see the recognition modal.
     */
    public function canRecognize(User $user): bool
    {
        if (!$user->relationLoaded('tl_employees')) {
            $user->load('tl_employees');
        }

        return $user->tl_employees->isNotEmpty();
    }

    public function shouldAutoShowRecognitionModal(User $user, ?int $days = null): bool
    {
        return $this->canRecognize($user) && $this->recognitionService->needsReminder($user, $days);
    }

    /**
     * Get the latest recognition for an employee (for congratulation card).
     */
    public function getLatestRecognitionForUser(User $user, int $days = 30)
    {
        return Recognition::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Get users with birthdays in the next given number of days.
     * Attaches two dynamic properties on each user:
     * - days_until_birthday
     * - next_birthday_date
     */
    public function getUpcomingBirthdays(int $days = 15): Collection
    {
        $today = Carbon::today();

        $users = User::with(['employee', 'media'])
            ->where('status', 'Active')
            ->whereNotIn('id', [1, 2])
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('birth_date');
            })
            ->get();

        $upcoming = $users->map(function ($user) use ($today) {
            $birthDate = Carbon::parse(optional($user->employee)->birth_date);

            if (!$birthDate) {
                return null;
            }

            // Handle leap day birthdays gracefully on non-leap years (treat as Feb 28)
            $birthMonth = $birthDate->month;
            $birthDay = $birthDate->day;
            if ($birthMonth === 2 && $birthDay === 29 && !Carbon::isLeapYear($today->year)) {
                $birthDay = 28;
            }

            $nextBirthday = Carbon::create($today->year, $birthMonth, $birthDay);
            if ($nextBirthday->isPast()) {
                $targetYear = $today->year + 1;
                if ($birthMonth === 2 && $birthDay === 29 && !Carbon::isLeapYear($targetYear)) {
                    $nextBirthday = Carbon::create($targetYear, 2, 28);
                } else {
                    $nextBirthday = Carbon::create($targetYear, $birthMonth, $birthDay);
                }
            }

            $daysRemaining = $today->diffInDays($nextBirthday, false);

            // Attach computed properties for view usage
            $user->days_until_birthday = $daysRemaining;
            $user->next_birthday_date = $nextBirthday;

            return $user;
        })
        ->filter(function ($user) use ($days) {
            return $user && $user->days_until_birthday >= 0 && $user->days_until_birthday <= $days;
        })
        ->sortBy('days_until_birthday')
        ->values();

        return $upcoming;
    }
}
