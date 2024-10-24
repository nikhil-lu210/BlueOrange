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

    public function clockIn($attendanceType)
    {
        $currentTime = now();
        $currentDate = $currentTime->toDateString();
        $type = $attendanceType === 'Overtime' ? 'Overtime' : 'Regular';

        // Check if the user has an open attendance session
        $openAttendance = Attendance::where('user_id', $this->user->id)->whereNull('clock_out')->first();

        if ($openAttendance) {
            throw new Exception('You have already clocked in and have not clocked out yet.');
        }

        // Check id the current date an weekend
        $isWeekend = Weekend::where('day', '=', Carbon::parse($currentDate)->format('l'))->where('is_active', true)->exists();
        if ($isWeekend) {
            throw new Exception('You cannot Regular clocin on Weekend. Please clockin as Overtime.');
        }

        // Check if the current date is a holiday
        $isHoliday = Holiday::where('date', '=', $currentDate)->where('is_active', true)->exists();
        if ($isHoliday) {
            throw new Exception('You cannot Regular clocin on Holiday. Please clockin as Overtime.');
        }

        // Check if the user has already Regular clocked in today
        $existingRegularAttendance = Attendance::where('user_id', $this->user->id)
            ->where('clock_in_date', $currentDate)
            ->whereType($type)
            ->first();
            
        if ($existingRegularAttendance && $existingRegularAttendance->type === 'Regular') {
            throw new Exception('You have already clocked in as Regular today.');
        }

        $location = Location::get(get_public_ip());

        DB::transaction(function () use ($currentTime, $currentDate, $type, $location) {
            Attendance::create([
                'user_id' => $this->user->id,
                'employee_shift_id' => $this->user->current_shift->id,
                'clock_in_date' => $currentDate,
                'clock_in' => $currentTime,
                'type' => $type,
                'ip_address' => $location->ip ?? null,
                'country' => $location->countryName ?? null,
                'city' => $location->cityName ?? null,
                'zip_code' => $location->zipCode ?? null,
                'time_zone' => $location->timezone ?? null,
                'latitude' => $location->latitude ?? null,
                'longitude' => $location->longitude ?? null
            ]);
        }, 5);
    }

    public function clockOut()
    {
        $currentTime = now();
        $userId = $this->user->id;

        // Retrieve the existing attendance record
        $existingAttendance = Attendance::where('user_id', $userId)
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

        // Update the existing attendance record with clock_out time and calculate total time
        $clockOutTime = $currentTime->timestamp;
        $clockInTime = $existingAttendance->clock_in->timestamp;

        // Calculate total time in seconds
        $totalSeconds = $clockOutTime - $clockInTime;

        // Convert total time to HH:MM:SS format
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $formattedTotalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Check attendance type
        if ($existingAttendance->type === 'Regular') {
            // Retrieve the employee shift associated with this user (assuming you have a relation set up)
            $employeeShift = $existingAttendance->employee_shift;

            if ($employeeShift) {
                // Convert employee shift total time from HH:MM:SS to seconds for comparison
                list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $employeeShift->total_time);
                $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;

                // Compare and set total_adjusted_time
                $adjustedTotalSeconds = ($totalSeconds < $shiftTotalSeconds) ? $totalSeconds : $shiftTotalSeconds;

                // Convert adjusted total time back to HH:MM:SS format
                $adjustedHours = floor($adjustedTotalSeconds / 3600);
                $adjustedMinutes = floor(($adjustedTotalSeconds % 3600) / 60);
                $adjustedSeconds = $adjustedTotalSeconds % 60;

                $formattedAdjustedTotalTime = sprintf('%02d:%02d:%02d', $adjustedHours, $adjustedMinutes, $adjustedSeconds);
            } else {
                // If no employee shift found, fallback to the total time
                $formattedAdjustedTotalTime = $formattedTotalTime;
            }
        } elseif ($existingAttendance->type === 'Overtime') {
            // For Overtime type, set total_adjusted_time directly from total_time
            $formattedAdjustedTotalTime = $formattedTotalTime;
        }

        // Update the existing attendance record with clock_out time, total_time, and total_adjusted_time
        $existingAttendance->update([
            'clock_out' => $clockOutTime,
            'total_time' => $formattedTotalTime,
            'total_adjusted_time' => $formattedAdjustedTotalTime,
        ]);
    }


}
