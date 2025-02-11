<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;
use App\Exports\Administration\Attendance\AttendanceExport;
use App\Services\Administration\Attendance\AttendanceService;
use App\Services\Administration\Attendance\AttendanceEntryService;
use App\Http\Requests\Administration\Attendance\AttendanceUpdateRequest;

class AttendanceController extends Controller
{
    protected $timeZone;

    public function __construct() {
        // Get the current timezone set in the system (PHP default)
        $this->timeZone = date_default_timezone_get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::select(['id', 'name'])
                     ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                     ->whereStatus('Active')
                     ->get();

        $attendances = $this->getAttendancesQuery($request)
                            ->orderByDesc('clock_in')
                            ->get();

        $total = null;
        if ($request->user_id) {
            // Inside your controller
            $attendanceService = new AttendanceService();
            $user = User::whereId($request->user_id)->firstOrFail();
            $month = $request->created_month_year ? Carbon::createFromFormat('F Y', $request->created_month_year) : Carbon::now()->format('Y-m-d');

            $total['regularWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Regular', $month);
            $total['overtimeWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Overtime', $month);
            $total['breakTime'] = $attendanceService->userTotalBreakTime($user, $attendances);
            $total['overBreakTime'] = $attendanceService->userTotalOverBreakTime($user, $attendances);

            // dd($total['regularWorkedHours']);
        }

        // Check if the user has already clocked in today
        $clockedIn = $this->getUserClockedInStatus(auth()->user()->id);

        return view('administration.attendance.index', compact('users', 'attendances', 'clockedIn', 'total'));
    }

    /**
     * Display a listing of the authenticated user's attendances.
     */
    public function myAttendances(Request $request)
    {
        $user = auth()->user();
        $attendances = $this->getAttendancesQuery($request, $user->id)
                            ->latest()
                            ->distinct()
                            ->get();

        // Inside your controller
        $attendanceService = new AttendanceService();
        $month = $request->created_month_year ? Carbon::createFromFormat('F Y', $request->created_month_year) : Carbon::now()->format('Y-m-d');

        $total['regularWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Regular', $month);
        $total['overtimeWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Overtime', $month);
        $total['breakTime'] = $attendanceService->userTotalBreakTime($user, $attendances);
        $total['overBreakTime'] = $attendanceService->userTotalOverBreakTime($user, $attendances);

        // Check if the user has already clocked in today
        $clockedIn = $this->getUserClockedInStatus($user->id);

        return view('administration.attendance.my', compact('attendances', 'clockedIn', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select(['id', 'name'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->get();

        return view('administration.attendance.create', compact(['users']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fetch the user
        $user = User::whereId($request->user_id)->whereStatus('Active')->firstOrFail();

        // Create Carbon instances for clockIn and clockOut
        $clockIn = Carbon::parse("{$request->clock_in_date} {$request->clock_in}:00");
        $clockOut = Carbon::parse("{$request->clock_in_date} {$request->clock_out}:00");

        // Handle cases where clock-out is past midnight (next day)
        if ($clockOut < $clockIn) {
            $clockOut->addDay();
        }

        try {
            // Use DB transaction to wrap the process
            DB::transaction(function () use ($user, $request, $clockIn, $clockOut) {
                // Initialize the service
                $attendanceEntryService = new AttendanceEntryService($user);

                // Handle clock-in process and get the Attendance object
                $attendanceClockIn = $attendanceEntryService->clockIn($request->type, $request->clock_in_date, $clockIn, 'Manual');

                // Handle clock-out process using the Attendance object
                $attendanceEntryService->clockOut($attendanceClockIn, $clockOut, 'Manual');
            });

            // Final attendance entry processing can go here if needed
            toast('Clocked In and Out Successfully for ' . $user->name . ' on ' . $request->clock_in_date . '.', 'success');
            return redirect()->back();

        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    
    // Clockin
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $attendanceType = $request->attendance;

        try {
            $attendanceService = new AttendanceEntryService($user);
            $attendanceService->clockIn($attendanceType);

            toast('Clocked In Successful.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    // Clockout
    public function clockOut()
    {
        $user = auth()->user();

        try {
            $attendanceService = new AttendanceEntryService($user);
            $attendanceService->clockOut();

            toast('Clocked Out Successful.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return view('administration.attendance.show', compact(['attendance']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        try {
            // Initialize the service
            $attendanceEntryService = new AttendanceEntryService($attendance->user);

            // Parse clock-in and clock-out times
            $clockIn = Carbon::parse($request->clock_in);
            $clockOut = $request->clock_out ? Carbon::parse($request->clock_out) : null;

            // Handle clock-in process and get the updated Attendance object
            $attendanceEntryService->updateClockIn($attendance, $request->type, $clockIn);

            if ($clockOut) {
                // Handle clock-out process and get the updated Attendance object
                $attendanceEntryService->updateClockOut($attendance, $clockIn, $clockOut);
            }

            // Save updated attendance record
            $attendance->save();

            toast('Attendance Record Updated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * export attendances.
     */
    public function export(Request $request)
    {
        // Building the query based on filters
        $query = Attendance::with([
            'user:id,name',
            'employee_shift:id,start_time,end_time'
        ])
        ->whereHas('user')
        ->orderBy(User::select('name')->whereColumn('users.id', 'attendances.user_id')); // order by asc

        // Initialize variables for filename parts
        $userName = '';
        $monthYear = '';
        $clockinType = '';
        
        // Handle user_id filter
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            $userName = $user ? '_of_' . strtolower(str_replace(' ', '_', $user->name)) : '';
        }

        // Handle created_month_year filter
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYearDate = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('clock_in', $monthYearDate->year)
                ->whereMonth('clock_in', $monthYearDate->month);
            $monthYear = '_of_' . $monthYearDate->format('m_Y');
        } else {
            // dd(Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d'));
            if (!$request->has('filter_attendance')) {
                $query->whereBetween('clock_in_date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }
        
        // Handle type filter
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
            
            $clockinType = strtolower($request->type). '_';
        }

        // Get the filtered attendances
        $attendances = $query->get();

        if ($attendances->count() < 1) {
            toast('There is no attendances to download.', 'warning');
            return redirect()->back();
        }

        $downloadMonth = $monthYear ? $monthYear : '_'.date('m_Y');
        $fileName = $clockinType . 'attendances_backup' . $userName . $downloadMonth . '.xlsx';

        // Return the Excel download with the appropriate filename
        return Excel::download(new AttendanceExport($attendances), $fileName);
    }



    /**
     * Build the attendance query based on the request filters.
     */
    private function getAttendancesQuery(Request $request, $userId = null)
    {
        $query = Attendance::with([
            'user:id,userid,name,first_name,last_name',
            'user.media', 
            'user.roles', 
            'employee_shift:id,start_time,end_time',
            'daily_breaks'
        ]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Filter by user_id if provided
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by created month and year if provided
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('clock_in', $monthYear->year)
                  ->whereMonth('clock_in', $monthYear->month);
        } else {
            // Apply default filter if no filter_attendance is set
            if (!$request->has('filter_attendance')) {
                $query->whereBetween('clock_in_date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Filter by type if provided
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        return $query;
    }

    /**
     * Check if the user has already clocked in today.
     */
    private function getUserClockedInStatus($userId)
    {
        return Attendance::where('user_id', $userId)
                         ->whereNull('clock_out')
                         ->first();
    }
}
