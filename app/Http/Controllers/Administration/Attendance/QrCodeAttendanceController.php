<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;
use App\Models\EmployeeShift\EmployeeShift;

class QrCodeAttendanceController extends Controller
{
    public function scanner()
    {
        $scanner = auth()->user();

        // Get the start and end of today
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        // Query to get attendances created today
        $attendances = Attendance::with([
            'user:id,userid,name', 
            'user.media', 
            'user.roles', 
            'employee_shift:id,start_time,end_time'
        ])
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->orderByDesc('clock_in')
        ->get();

        return view('administration.attendance.qr_scanner', compact(['scanner', 'attendances']));
    }

    public function scanQrCode($scanner_id, $qr_code, $type = 'Regular')
    {
        if ($scanner_id != auth()->user()->userid) {
            toast('You are not authorized to scan code.', 'warning');
            return redirect()->back();
        }

        $user = User::where('userid', $qr_code)->firstOrFail();
        $currentTime = now();
        $currentDate = $currentTime->toDateString();
        
        // Check if the user has an open attendance session (clocked in but not clocked out)
        $openAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        // Determine if the action is clock-in or clock-out
        if ($openAttendance) {
            // User is currently clocked in, so this should be a clock-out
            return $this->clockOut($user->id, $currentTime);
        } else {
            // No open attendance record, so this should be a clock-in
            return $this->clockIn($user->id, $currentTime, $currentDate, $type);
        }
    }

    private function clockIn($userId, $currentTime, $currentDate, $type = 'Regular')
    {
        // Check if the user has already clocked in today
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('clock_in_date', $currentDate)
            ->whereType($type)
            ->first();

        if ($existingAttendance) {
            toast(User::find($userId)->name. ' has already been '.$type.' clocked in today.', 'warning');
            return redirect()->back();
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

        $location = Location::get(get_public_ip());

        try {
            DB::transaction(function() use ($userId, $currentTime, $location, $currentDate, $type) {
                Attendance::create([
                    'user_id' => $userId,
                    'employee_shift_id' => User::find($userId)->current_shift->id,
                    'clock_in_date' => $currentDate,
                    'clock_in' => $currentTime,
                    'type' => $type,
                    'qr_clockin_scanner_id' => auth()->user()->id,
                    'ip_address' => $location->ip ?? null,
                    'country' => $location->countryName ?? null,
                    'city' => $location->cityName ?? null,
                    'zip_code' => $location->zipCode ?? null,
                    'time_zone' => $location->timezone ?? null,
                    'latitude' => $location->latitude ?? null,
                    'longitude' => $location->longitude ?? null
                ]);
            }, 5);

            toast(User::find($userId)->name.' Clocked In Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    private function clockOut($userId, $currentTime)
    {
        // Retrieve the existing attendance record
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereNull('clock_out')
            ->first();

        if (!$existingAttendance) {
            toast(User::find($userId)->name . ' has not been regular clocked in today.', 'warning');
            return redirect()->back();
        }

        // Update the existing attendance record with clock_out time and calculate total time
        try {
            $clockOutTime = $currentTime->timestamp; // Use timestamp
            $clockInTime = $existingAttendance->clock_in->timestamp;

            // Calculate total time in seconds
            $totalSeconds = $clockOutTime - $clockInTime;

            // Convert total time to HH:MM:SS format
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            $formattedTotalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            // Initialize variable for total_adjusted_time
            $formattedAdjustedTotalTime = $formattedTotalTime;

            // Check attendance type
            if ($existingAttendance->type === 'Regular') {
                // Retrieve the employee shift associated with this user (assuming you have a relation set up)
                $employeeShift = EmployeeShift::where('user_id', $userId)->first();

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
                }
            }

            // For 'Overtime' type, total_adjusted_time is the same as total_time, so no need to update it further

            // Update the attendance record with clock_out, total_time, and total_adjusted_time
            $existingAttendance->update([
                'clock_out' => $clockOutTime,
                'total_time' => $formattedTotalTime,
                'total_adjusted_time' => $formattedAdjustedTotalTime,
                'qr_clockout_scanner_id' => auth()->user()->id,
            ]);

            toast(User::find($userId)->name . ' Clocked Out Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
