<?php

namespace App\Http\Controllers\Administration\Attendance\Issue;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;
use App\Models\Attendance\Issue\AttendanceIssue;
use App\Http\Requests\Administration\Attendance\Issue\AttendanceIssueStoreRequest;
use App\Http\Requests\Administration\Attendance\Issue\AttendanceIssueUpdateRequest;
use App\Mail\Administration\Attendance\Issue\AttendanceIssueStatusUpdateMail;
use App\Mail\Administration\Attendance\Issue\NewAttendanceIssueMail;
use App\Notifications\Administration\Attendance\Issue\AttendanceIssueRequestUpdateNotification;
use App\Notifications\Administration\Attendance\Issue\AttendanceIssueStoreNotification;

class AttendanceIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        $teamLeaders = User::whereIn('id', $userIds)
                            ->whereStatus('Active')
                            ->get()
                            ->filter(function ($user) {
                                return $user->hasAnyPermission(['Attendance Everything', 'Attendance Update']);
                            });

        // Eager load all necessary relationships
        $users = User::with(['roles', 'media', 'shortcuts', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->get(['id', 'name']);

        // Set default date range (current month)
        $startOfMonth = now()->startOfMonth()->format('Y-m-d'); // First day of the current month
        $today = now()->format('Y-m-d'); // Today's date

        $query = AttendanceIssue::with([
                                        'user:id,userid,name',
                                        'user.media',
                                        'user.employee',
                                        'user.roles',
                                    ])->orderByDesc('clock_in_date');

        // If a team leader ID is provided, filter employees under them
        if ($request->team_leader_id) {
            $teamLeader = User::find($request->team_leader_id);
            if ($teamLeader) {
                $employeeIds = $teamLeader->tl_employees->pluck('id');
                $query->whereIn('user_id', $employeeIds);
            }
        }

        // Apply user filter
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Handle month/year filtering
        if ($request->has('issue_month_year') && !is_null($request->issue_month_year)) {
            $monthYear = Carbon::parse($request->issue_month_year);
            $query->whereYear('clock_in_date', $monthYear->year)
                ->whereMonth('clock_in_date', $monthYear->month);
        } else {
            // Default to current month if no specific filter is applied
            if (!$request->has('filter_issues')) {
                $query->whereBetween('clock_in_date', [$startOfMonth, $today]);
            }
        }

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        // Apply status filter if specified
        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        }

        $issues = $query->get();

        return view('administration.attendance.issue.index', compact(['teamLeaders', 'users', 'issues']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $startOfMonth = now()->startOfMonth()->format('Y-m-d'); // First day of the current month
        $today = now()->format('Y-m-d'); // Today's date

        $issues = AttendanceIssue::with([
                                        'user:id,userid,name',
                                        'user.media',
                                        'user.employee',
                                        'user.roles',
                                    ])->whereUserId(auth()->user()->id)
                                ->whereBetween('clock_in_date', [$startOfMonth, $today])
                                ->orderByDesc('clock_in_date')
                                ->get();
        // dd($issues);
        return view('administration.attendance.issue.my', compact(['issues']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $startDate = now()->subDays(44)->format('Y-m-d'); // 45 days including today
        $today = now()->format('Y-m-d'); // Today's date

        // Generate last 45 days' dates
        $dates = collect(range(0, 44))->map(function ($day) {
            return now()->subDays($day)->format('Y-m-d');
        })->values();

        // Fetch attendances within the last 45 days
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereBetween('clock_in_date', [$startDate, $today])
            ->orderByDesc('clock_in_date')
            ->get();

        return view('administration.attendance.issue.create', compact(['dates', 'attendances']));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceIssueStoreRequest $request)
    {
        // dd($request->all(), auth()->user()->active_team_leader);
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
            $issue = null;
            DB::transaction(function () use ($request, $userId, $clockInDate, $shiftId, $attendanceId, &$issue) {
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

                // Send Notification to Team Leader
                auth()->user()->active_team_leader->notify(new AttendanceIssueStoreNotification($issue, auth()->user()));

                // Send Mail to the Team Leader
                Mail::to(auth()->user()->active_team_leader->employee->official_email)->send(new NewAttendanceIssueMail($issue, auth()->user()->active_team_leader));
            }, 5);

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

                // Send Notification to Issue Applier
                $issue->user->notify(new AttendanceIssueRequestUpdateNotification($issue, auth()->user()));

                // Send Mail to the Issue Applier by Queue
                Mail::to($issue->user->employee->official_email)->queue(new AttendanceIssueStatusUpdateMail($issue, auth()->user()));

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

                // Send Notification to Issue Applier
                $issue->user->notify(new AttendanceIssueRequestUpdateNotification($issue, auth()->user()));

                // Send Mail to the Issue Applier by Queue
                Mail::to($issue->user->employee->official_email)->queue(new AttendanceIssueStatusUpdateMail($issue, auth()->user()));

                toast('Attendance Issue Approved and Attendance Updated.', 'success');
                return redirect()->back();
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
