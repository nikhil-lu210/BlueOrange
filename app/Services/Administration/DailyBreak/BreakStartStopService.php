<?php

namespace App\Services\Administration\DailyBreak;

use DB;
use Exception;
use Carbon\Carbon;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use Stevebauman\Location\Facades\Location;

class BreakStartStopService
{
    public function startBreak($user, $request)
    {
        $currentTime = Carbon::now();
        $currentDate = $currentTime->toDateString();

        // Check attendance
        $attendance = Attendance::where('user_id', $user->id)
            ->whereType('Regular') // ->where('clock_in_date', $currentDate)
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            throw new Exception('You cannot take any break as you have not REGULAR clocked in today or already clocked out.');
        }

        // Check shift constraints
        // $this->checkShiftTimeConstraints($user, $currentTime);

        // Check clock-in/clock-out constraints
        $this->checkClockTimeConstraints($user, $currentTime);

        // Count breaks
        $this->checkBreakLimits($user, $attendance, $request->break_type);

        // Check last break
        $this->checkLastBreak($user, $attendance, $currentTime);

        // Get IP location
        $location = Location::get(get_public_ip());

        // Start break transaction
        return DB::transaction(function() use ($user, $attendance, $currentTime, $location, $currentDate, $request) {
            return DailyBreak::create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
                'date' => $currentDate,
                'break_in_at' => $currentTime,
                'break_in_ip' => $location->ip ?? 'N/A',
                'type' => $request->break_type,
            ]);
        }, 5);
    }

    public function stopBreak($user)
    {
        $currentTime = now();

        // Retrieve the existing Break record
        $existingBreak = DailyBreak::where('user_id', $user->id)
            ->whereNull('break_out_at')
            ->first();

        if (!$existingBreak) {
            throw new Exception('You have no running break.');
        }

        $location = Location::get(get_public_ip());
        $breakStopTime = $currentTime->timestamp; // Use timestamp
        $breakStartTime = $existingBreak->break_in_at->timestamp;

        // Calculate total time in seconds
        $totalSeconds = $breakStopTime - $breakStartTime;

        // Convert total time to HH:MM:SS format
        $formattedTotalTime = $this->formatTime($totalSeconds);

        return DB::transaction(function() use ($existingBreak, $location, $breakStopTime, $formattedTotalTime) {
            // Update the existing break record
            $updateSuccess = $existingBreak->update([
                'break_out_at' => $breakStopTime,
                'total_time' => $formattedTotalTime,
                'break_out_ip' => $location->ip ?? 'N/A',
            ]);

            // Check if the update was successful
            if ($updateSuccess) {
                // Calculate the overbreak and update the record
                $existingBreak->over_break = over_break($existingBreak->id);
                $existingBreak->save(); // Save the updated over_break value
            }
        });
    }

    private function formatTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function checkShiftTimeConstraints($user, $currentTime)
    {
        $shift = $user->current_shift;

        if ($shift) {
            // Parse the shift start and end times
            $shiftStartTime = Carbon::parse($shift->start_time);
            $shiftEndTime = Carbon::parse($shift->end_time);

            // Check if the break is within the first or last hour of the shift
            if (
                $currentTime->between($shiftStartTime, $shiftStartTime->copy()->addHour()) ||
                $currentTime->between($shiftEndTime->copy()->subHour(), $shiftEndTime)
            ) {
                throw new Exception('You cannot take a break in the first or last hour of your shift.');
            }
        }
    }

    private function checkClockTimeConstraints($user, $currentTime)
    {
        $shift = $user->current_shift;

        if ($shift) {
            // Fetch the latest "Regular" type attendance where clock_out is null
            $attendance = $user->attendances()
                ->whereType('Regular')
                ->whereNull('clock_out')
                ->latest()
                ->first();

            // Check if attendance exists
            if ($attendance) {
                // Parse the clock_in time from the attendance record
                $clockInTime = Carbon::parse($attendance->clock_in);

                // Calculate the shift duration (in seconds) using the shift start and end times
                $shiftStartTime = Carbon::parse($shift->start_time);
                $shiftEndTime = Carbon::parse($shift->end_time);
                $shiftDurationInSeconds = $shiftEndTime->diffInSeconds($shiftStartTime);

                // Calculate the expected clock-out time based on the clock-in time and the shift duration
                $expectedClockOutTime = $clockInTime->copy()->addSeconds($shiftDurationInSeconds);

                // Define the time range where breaks are not allowed
                $firstHourEnd = $clockInTime->copy()->addHour(); // First hour after clock-in
                $lastHourStart = $expectedClockOutTime->copy()->subHour(); // Last hour before clock-out

                // Check if the current time is within the first hour after clock-in or the last hour before clock-out
                if (
                    $currentTime->between($clockInTime, $firstHourEnd) || // First hour after clock-in
                    $currentTime->between($lastHourStart, $expectedClockOutTime) // Last hour before clock-out
                ) {
                    throw new Exception('You cannot take a break as it is the first or last hour of your today\'s clockin.');
                }
            } else {
                // Optionally handle cases where there is no relevant attendance record
                throw new Exception('No valid attendance record found for the user.');
            }
        }
    }

    private function checkBreakLimits($user, $attendance, $breakType)
    {
        $shortBreakCount = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->where('type', 'Short')
            ->count();

        $longBreakCount = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->where('type', 'Long')
            ->count();

        if ($breakType == 'Short' && $shortBreakCount >= 2) {
            throw new Exception('You have already taken 2 Short Breaks today.');
        }

        if ($breakType == 'Long' && $longBreakCount >= 1) {
            throw new Exception('You have already taken your Long Break today.');
        }
    }

    private function checkLastBreak($user, $attendance, $currentTime)
    {
        $lastBreak = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->orderBy('break_out_at', 'desc')
            ->first();

        if ($lastBreak && $currentTime->diffInHours(Carbon::parse($lastBreak->break_out_at)) < 1) {
            throw new Exception('You cannot take another break within 1 hour of your previous break.');
        }
    }
}
