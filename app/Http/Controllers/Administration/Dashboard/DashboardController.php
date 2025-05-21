<?php

namespace App\Http\Controllers\Administration\Dashboard;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Leave\LeaveHistory;

use App\Services\Administration\Attendance\AttendanceService;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with(['employee'])->whereId(auth()->user()->id)->firstOrFail();

        // Pick a random birthday wish
        $wish = $this->randomBirthdayWish();

        // Create an instance of AttendanceService
        $attendanceService = new AttendanceService();

        // Get total worked days
        $totalWorkedDays = $attendanceService->calculateTotalWorkedDays($user);

        // Get total Regular worked days
        $totalRegularWork = $attendanceService->calculateTotalWork($user, 'Regular');

        // Get total Overtime worked days
        $totalOvertimeWork = $attendanceService->calculateTotalWork($user, 'Overtime');

        // Get total working hour (Regular)
        $totalRegularWorkingHour = $attendanceService->totalWorkingHour($user, 'Regular');

        // Get total working hour (Overtime)
        $totalOvertimeWorkingHour = $attendanceService->totalWorkingHour($user, 'Overtime');

        $activeAttendance = Attendance::select(['id', 'user_id', 'type', 'clock_in', 'clock_out'])
                                    ->whereUserId($user->id)
                                    ->whereNull('clock_out')
                                    ->latest()
                                    ->first();

        $attendances = Attendance::whereUserId($user->id)
                                    ->whereBetween('clock_in_date', [
                                        Carbon::now()->startOfMonth()->format('Y-m-d'),
                                        Carbon::now()->endOfMonth()->format('Y-m-d')
                                    ])
                                    ->orderByDesc('clock_in_date')
                                    ->orderByDesc('created_at')
                                    ->get();

        // Get users who are currently working (clocked in but not clocked out)
        $currentlyWorkingUsers = User::with(['employee', 'media'])
                                    ->where('status', 'Active')
                                    ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
                                    ->whereHas('attendances', function($query) {
                                        $query->whereDate('clock_in_date', Carbon::today())
                                              ->whereNull('clock_out');
                                    })
                                    ->get();

        // Get users who are on leave today (pending or approved)
        $onLeaveUsers = User::with(['employee', 'media'])
                            ->where('status', 'Active')
                            ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
                            ->whereHas('leave_histories', function($query) {
                                $query->whereDate('date', Carbon::today())
                                      ->whereIn('status', ['Pending', 'Approved']);
                            })
                            ->get();

        // Get users who are absent today (shift started/running/passed but no attendance)
        $today = Carbon::today();
        $currentTime = Carbon::now();

        // First, get all users with active shifts
        $usersWithActiveShifts = User::with(['employee', 'media', 'employee_shifts'])
                                    ->where('status', 'Active')
                                    ->whereNotIn('id', [1, 2]) // Exclude Developer and Controller users
                                    ->whereHas('employee_shifts', function($query) {
                                        $query->where('status', 'Active');
                                    })
                                    ->get();

        // Filter users whose shift has started but have no attendance today
        $absentUsers = $usersWithActiveShifts->filter(function($user) use ($today, $currentTime) {
            // Get the active shift for this user
            $activeShift = $user->employee_shifts()
                ->where('status', 'Active')
                ->latest('created_at')
                ->first();

            // Check if user has an active shift
            if (!$activeShift) {
                return false;
            }

            // Get shift start time for today
            $shiftStartTime = Carbon::parse($today->format('Y-m-d') . ' ' . $activeShift->start_time);

            // Check if shift has started
            if ($currentTime->lt($shiftStartTime)) {
                return false;
            }

            // Check if user has attendance record for today
            $hasAttendance = Attendance::where('user_id', $user->id)
                                      ->whereDate('clock_in_date', $today)
                                      ->exists();

            // Check if user is on leave today
            $isOnLeave = LeaveHistory::where('user_id', $user->id)
                                    ->whereDate('date', $today)
                                    ->whereIn('status', ['Pending', 'Approved'])
                                    ->exists();

            // User is absent if shift has started, but no attendance and not on leave
            return !$hasAttendance && !$isOnLeave;
        });

        return view('administration.dashboard.index', compact([
            'user',
            'wish',
            'totalWorkedDays',
            'totalRegularWork',
            'totalRegularWorkingHour',
            'totalOvertimeWorkingHour',
            'totalOvertimeWork',
            'activeAttendance',
            'attendances',
            'currentlyWorkingUsers',
            'onLeaveUsers',
            'absentUsers',
        ]));
    }


    private function randomBirthdayWish()
    {
        // Predefined array of birthday wishes
        $birthdayWishes = [
            "May your birthday be the start of a year filled with good luck, good health, and much happiness.",
            "Enjoy your special day with all the people you love.",
            "Wishing you a day that’s as special as you are!",
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
}
