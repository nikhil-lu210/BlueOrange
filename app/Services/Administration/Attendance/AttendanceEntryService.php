<?php
namespace App\Services\Administration\Attendance;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
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
        $openAttendance = Attendance::where('user_id', $this->user->id)
            ->whereNull('clock_out')
            ->first();

        if ($openAttendance) {
            throw new Exception('You have already clocked in and have not clocked out yet.');
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

        $existingAttendance->update([
            'clock_out' => $clockOutTime,
            'total_time' => $formattedTotalTime,
        ]);
    }
}
