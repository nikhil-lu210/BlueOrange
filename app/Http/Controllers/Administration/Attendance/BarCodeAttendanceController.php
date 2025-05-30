<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance\Attendance;
use App\Http\Controllers\Controller;
use App\Services\Administration\Attendance\AttendanceEntryService;
use Illuminate\Http\Request;

class BarCodeAttendanceController extends Controller
{
    public function scanner()
    {
        $scanner_id = auth()->user()->userid;

        $hours = 24;
        // Get the start and end of last $hours hours
        $endTime = Carbon::now();
        $startTime = Carbon::now()->subHours($hours);

        // Query to get attendances from last $hours hours
        $attendances = Attendance::with([
            'user:id,userid,name',
            'user.employee',
            'user.media',
            'user.roles',
            'employee_shift:id,start_time,end_time'
        ])
        ->whereBetween('clock_in', [$startTime, $endTime])
        ->orderByDesc('updated_at')
        ->get();

        return view('administration.attendance.barcode_scanner', compact(['scanner_id', 'attendances', 'hours']));
    }

    public function scanBarCode(Request $request, $scanner_id)
    {
        if ($scanner_id != auth()->user()->userid) {
            toast('You are not authorized to scan code.', 'warning');
            return redirect()->back();
        }

        $user = User::where('userid', $request->input('userid'))->firstOrFail();
        $currentTime = now();
        $currentDate = $currentTime->toDateString();

        // Check if the user has an open attendance session (clocked in but not clocked out)
        $openAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        $attendanceEntry = new AttendanceEntryService($user);

        try {
            // Determine if the action is clock-in or clock-out
            if ($openAttendance || $request->input('attendance') == 'Clockout') {
                // User is currently clocked in, so this should be a clock-out
                $attendanceEntry->clockOut($openAttendance, $currentTime, 'Barcode', auth()->user()->id);

                toast($user->employee->alias_name . ' Clocked Out Successfully.', 'success');
                return redirect()->back();
            } else {
                // No open attendance record, so this should be a clock-in
                $attendanceEntry->clockIn($request->type, $currentDate, $currentTime, 'Barcode', auth()->user()->id);

                toast($user->employee->alias_name . ' Clocked In Successfully.', 'success');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toast('Error: '.$e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

