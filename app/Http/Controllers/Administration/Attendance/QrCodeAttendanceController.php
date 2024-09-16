<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;

class QrCodeAttendanceController extends Controller
{
    public function scanner()
    {
        $scanner = auth()->user();
        return view('administration.attendance.qr_scanner', compact(['scanner']));
    }

    public function scanQrCode($scanner_id, $qr_code)
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
            return $this->clockIn($user->id, $currentTime, $currentDate, $scanner_id);
        }
    }

    private function clockIn($userId, $currentTime, $currentDate, $scannerId)
    {
        $type = request()->attendance === 'Overtime' ? 'Overtime' : 'Regular';

        // Check if the user has already clocked in today
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('clock_in_date', $currentDate)
            ->whereType($type)
            ->first();

        if ($existingAttendance) {
            toast(User::find($userId)->name. ' has been already '.$type.' clocked in today.', 'warning');
            return redirect()->back();
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
            ->whereType('Regular')
            ->first();

        if (!$existingAttendance) {
            toast(User::find($userId)->name. 'Has not regular clocked in today.', 'warning');
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

            $existingAttendance->update([
                'clock_out' => $clockOutTime,
                'total_time' => $formattedTotalTime,
            ]);

            toast(User::find($userId)->name. 'Clocked Out Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
