<?php

namespace App\Http\Controllers\Administration\Attendance\Issue;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
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
        // dd($request->all(), $issue->toArray(), $status);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceIssue $issue)
    {
        dd($issue->toArray());
    }
}
