<?php
namespace App\Services\Administration\Attendance;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;
use Stevebauman\Location\Facades\Location;
use App\Services\Administration\DailyBreak\BreakStartStopService;

class AttendanceEntryService
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function clockIn($attendanceType, $clockInDate = null, $clockInTime = null, $clockInMedium = 'Manual')
    {
        $currentTime = now();
        $currentDate = $clockInDate ?? $currentTime->toDateString(); // Use provided date or current date
        $clockInTimestamp = $clockInTime ? Carbon::parse($clockInTime) : $currentTime; // Use provided time or current time
        $type = $attendanceType === 'Overtime' ? 'Overtime' : 'Regular';
        // dd($attendanceType, $type, $clockInDate, $clockInTime, $clockInMedium);

        // Check if the user has an open attendance session
        $openAttendance = Attendance::where('user_id', $this->user->id)->whereNull('clock_out')->first();

        if ($openAttendance) {
            throw new Exception('You have already clocked in and have not clocked out yet.');
        }

        // Check if the current date is a weekend or holiday, and if the user is trying to clock in as Regular
        if ($this->isWeekend($currentDate) && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Weekend. Please clock in as Overtime.');
        }

        if ($this->isHoliday($currentDate) && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Holiday. Please clock in as Overtime.');
        }

        // Check if the user has already Regular clocked in today
        $existingRegularAttendance = Attendance::where('user_id', $this->user->id)
            ->where('clock_in_date', $currentDate)
            ->where('type', 'Regular')
            ->first();
            
        if ($existingRegularAttendance && $type === 'Regular') {
            throw new Exception('You have already clocked in as Regular today.');
        }

        $location = Location::get(get_public_ip());

        $attendance = DB::transaction(function () use ($clockInTimestamp, $currentDate, $type, $clockInMedium, $location) {
            return Attendance::create([
                'user_id' => $this->user->id,
                'employee_shift_id' => $this->user->current_shift->id,
                'clock_in_date' => $currentDate,
                'clock_in' => $clockInTimestamp,
                'type' => $type,
                'clockin_medium' => $clockInMedium, // Now using the provided clock-in medium
                'ip_address' => $location->ip ?? null,
                'country' => $location->countryName ?? null,
                'city' => $location->cityName ?? null,
                'zip_code' => $location->zipCode ?? null,
                'time_zone' => $location->timezone ?? null,
                'latitude' => $location->latitude ?? null,
                'longitude' => $location->longitude ?? null
            ]);
        }, 5);

        return $attendance;
    }

    public function clockOut(Attendance $attendance = null, $clockOutTime = null, $clockOutMedium = 'Manual')
    {
        $currentTime = now();
        $userId = $this->user->id;

        // If no attendance record is passed, retrieve the active attendance
        $existingAttendance = $attendance ?? Attendance::where('user_id', $userId)
            ->whereNull('clock_out')
            ->first();

        if (!$existingAttendance) {
            throw new Exception('You have not clocked in today.');
        }

        // Stop any active running breaks
        $activeRunningBreak = DailyBreak::where('user_id', $userId)
            ->where('attendance_id', $existingAttendance->id)
            ->whereNull('break_out_at')
            ->first();

        if ($activeRunningBreak) {
            $breakStartStopService = new BreakStartStopService();
            $breakStartStopService->stopBreak($this->user);
        }

        // Use the provided clock-out time or current time if not provided
        $clockOutTime = $clockOutTime ? Carbon::parse($clockOutTime)->timestamp : $currentTime->timestamp;
        $clockInTime = $existingAttendance->clock_in->timestamp;

        // Calculate total time in seconds
        $totalSeconds = $clockOutTime - $clockInTime;

        // Convert total time to HH:MM:SS format
        $formattedTotalTime = $this->formatTime($totalSeconds);

        // Check attendance type and adjust time accordingly
        if ($existingAttendance->type === 'Regular') {
            $formattedAdjustedTotalTime = $this->adjustForShiftTime($existingAttendance, $totalSeconds, $formattedTotalTime);
        } else {
            // For Overtime type, use the full time as is
            $formattedAdjustedTotalTime = $formattedTotalTime;
        }

        // Update the existing attendance record with clock_out time, total_time, and total_adjusted_time
        $existingAttendance->update([
            'clock_out' => $clockOutTime,
            'total_time' => $formattedTotalTime,
            'total_adjusted_time' => $formattedAdjustedTotalTime,
            'clockout_medium' => $clockOutMedium, // Now using the provided clock-out medium
        ]);
    }


    // Handle clock-in updates
    public function updateClockIn(Attendance $attendance, $type, Carbon $clockIn)
    {
        // Assign the updated clock-in time
        $attendance->clock_in = $clockIn;
        $attendance->type = $type;
    }

    // Handle clock-out updates
    public function updateClockOut(Attendance $attendance, Carbon $clockIn, Carbon $clockOut)
    {
        // Handle cases where clock-out is past midnight (next day)
        if ($clockOut < $clockIn) {
            $clockOut->addDay();
        }

        $attendance->clock_out = $clockOut;

        // Calculate total time in seconds
        $totalSeconds = $clockOut->diffInSeconds($clockIn);

        // Format total time as HH:MM:SS
        $formattedTotalTime = gmdate('H:i:s', $totalSeconds);

        // Initialize adjusted total time as total time
        $formattedAdjustedTotalTime = $formattedTotalTime;

        // Adjust total time based on employee shift
        if ($attendance->type === 'Regular') {
            $employeeShift = $attendance->employee_shift;

            if ($employeeShift) {
                // Convert shift total time to seconds
                list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $employeeShift->total_time);
                $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;

                // Use the minimum time (either worked time or shift time)
                $adjustedTotalSeconds = min($totalSeconds, $shiftTotalSeconds);

                // Format adjusted total time as HH:MM:SS
                $formattedAdjustedTotalTime = gmdate('H:i:s', $adjustedTotalSeconds);
            }
        }

        // Assign calculated values to the attendance object
        $attendance->total_time = $formattedTotalTime;
        $attendance->total_adjusted_time = $formattedAdjustedTotalTime;
    }


    // Check if the day is a weekend
    private function isWeekend($currentDate)
    {
        return Weekend::where('day', Carbon::parse($currentDate)->format('l'))->where('is_active', true)->exists();
    }

    // Check if the day is a holiday
    private function isHoliday($currentDate)
    {
        return Holiday::where('date', $currentDate)->where('is_active', true)->exists();
    }

    // Format time in seconds to HH:MM:SS format
    private function formatTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    // Adjust time based on shift
    private function adjustForShiftTime($existingAttendance, $totalSeconds, $formattedTotalTime)
    {
        // Retrieve the employee shift associated with this user
        $employeeShift = $existingAttendance->employee_shift;

        if ($employeeShift) {
            // Convert employee shift total time from HH:MM:SS to seconds for comparison
            list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $employeeShift->total_time);
            $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;

            // Compare and set total_adjusted_time
            $adjustedTotalSeconds = ($totalSeconds < $shiftTotalSeconds) ? $totalSeconds : $shiftTotalSeconds;

            // Convert adjusted total time back to HH:MM:SS format
            return $this->formatTime($adjustedTotalSeconds);
        } else {
            // If no employee shift found, fallback to the total time
            return $formattedTotalTime;
        }
    }
}
