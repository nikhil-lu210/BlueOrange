<?php

namespace App\Http\Controllers\Administration\Attendance\Issue;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;
use App\Models\Attendance\Issue\AttendanceIssue;
use App\Http\Requests\Administration\Attendance\Issue\AttendanceIssueStoreRequest;
use App\Http\Requests\Administration\Attendance\Issue\AttendanceIssueUpdateRequest;

class AttendanceIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startOfMonth = now()->startOfMonth()->format('Y-m-d'); // First day of the current month
        $today = now()->format('Y-m-d'); // Today's date

        $issues = AttendanceIssue::whereBetween('clock_in_date', [$startOfMonth, $today])
                                ->orderByDesc('clock_in_date')
                                ->get();
        // dd($issues);
        return view('administration.attendance.issue.index', compact(['issues']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $startOfMonth = now()->startOfMonth()->format('Y-m-d'); // First day of the current month
        $today = now()->format('Y-m-d'); // Today's date

        $dates = collect(range(0, now()->day - 1))->map(function ($day) {
            return now()->subDays($day)->format('Y-m-d');
        })->values();

        // Fetch attendances within the current month
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereBetween('clock_in_date', [$startOfMonth, $today])
            ->orderByDesc('clock_in_date')
            ->get();

        return view('administration.attendance.issue.create', compact(['dates', 'attendances']));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceIssueStoreRequest $request)
    {
        // dd($request->all());
        $userId = auth()->user()->id;
        $attendanceId = NULL;
        $clockInDate = NULL;
        $shiftId = auth()->user()->current_shift->id;

        if ($request->attendance_id) {
            $attendanceId = $request->attendance_id;
            $attendance = Attendance::whereId($attendanceId)->firstOrFail();

            $clockInDate = $attendance->clock_in_date;
            $shiftId = $attendance->employee_shift->id;
        }

        if ($request->clock_in_date) {
            $clockInDate = $request->clock_in_date;
        }

        try {
            $issue = AttendanceIssue::create([
                'user_id' => $userId,
                'attendance_id' => $attendanceId,
                'employee_shift_id' => $shiftId,
                'title' => $request->title,
                'clock_in_date' => $clockInDate,
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'reason' => $request->reason,
                'type' => $request->type,
            ]);

            toast('Attendance Issue Submitted.', 'success');
            return redirect()->route('administration.attendance.issue.show', ['issue' => $issue]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceIssue $issue)
    {
        return view('administration.attendance.issue.show', compact(['issue']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceIssueUpdateRequest $request, AttendanceIssue $issue, $status)
    {
        if ($status === 'Rejected' && $request->status === 'Rejected') {
            try {
                $issue->update([
                    'updated_by' => auth()->user()->id,
                    'note' => $request->note,
                    'status' => 'Rejected',
                ]);

                toast('Attendance Issue Has Been Rejected.', 'success');
                return redirect()->back();
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        if ($status === 'Approved' && $request->status === 'Approved') {
            try {
                // Fetch the user
                $user = User::whereId($issue->user->id)->whereStatus('Active')->firstOrFail();

                // Create Carbon instances for clockIn and clockOut
                $clockIn = Carbon::parse($request->clock_in);
                $clockOut = Carbon::parse($request->clock_out);

                // Handle cases where clock-out is past midnight (next day)
                if ($clockOut < $clockIn) {
                    $clockOut->addDay();
                }

                // Calculate total time
                $totalSeconds = $clockOut->diffInSeconds($clockIn);
                $formattedTotalTime = gmdate('H:i:s', $totalSeconds);

                // Default adjusted total time
                $formattedAdjustedTotalTime = $formattedTotalTime;

                if ($request->type === 'Regular') {
                    $employeeShift = $user->current_shift;

                    if ($employeeShift) {
                        list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $employeeShift->total_time);
                        $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;

                        $adjustedTotalSeconds = min($totalSeconds, $shiftTotalSeconds);
                        $formattedAdjustedTotalTime = gmdate('H:i:s', $adjustedTotalSeconds);
                    }
                }

                $location = Location::get(get_public_ip());

                // Check if attendance already exists
                if ($request->attendance_id && $issue->attendance_id) {
                    // Update existing attendance
                    $attendance = Attendance::findOrFail($request->attendance_id);
                    $attendance->update([
                        'clock_in_date' => $request->clock_in_date,
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                        'total_time' => $formattedTotalTime,
                        'total_adjusted_time' => $formattedAdjustedTotalTime,
                        'type' => $request->type,
                        'updated_by' => auth()->user()->id,
                        'ip_address' => $location->ip ?? null,
                        'country' => $location->countryName ?? null,
                        'city' => $location->cityName ?? null,
                        'zip_code' => $location->zipCode ?? null,
                        'time_zone' => $location->timezone ?? null,
                        'latitude' => $location->latitude ?? null,
                        'longitude' => $location->longitude ?? null,
                    ]);
                } else {
                    // Create new attendance record
                    $attendance = Attendance::create([
                        'user_id' => $user->id,
                        'employee_shift_id' => optional($user->current_shift)->id,
                        'clock_in_date' => $request->clock_in_date,
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                        'total_time' => $formattedTotalTime,
                        'total_adjusted_time' => $formattedAdjustedTotalTime,
                        'type' => $request->type,
                        'ip_address' => $location->ip ?? null,
                        'country' => $location->countryName ?? null,
                        'city' => $location->cityName ?? null,
                        'zip_code' => $location->zipCode ?? null,
                        'time_zone' => $location->timezone ?? null,
                        'latitude' => $location->latitude ?? null,
                        'longitude' => $location->longitude ?? null
                    ]);
                }

                // Update attendance issue status to Approved
                $issue->update([
                    'updated_by' => auth()->user()->id,
                    'status' => 'Approved',
                    'attendance_id' => $attendance->id,
                ]);

                toast('Attendance Issue Approved and Attendance Updated.', 'success');
                return redirect()->route('administration.attendance.show', ['attendance' => $attendance]);
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Invalid request.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceIssue $issue)
    {
        dd($issue->toArray());
    }
}
