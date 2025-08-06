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

    protected $authUser = null;

    public function __construct() {
        // Get the current timezone set in the system (PHP default)
        $this->timeZone = date_default_timezone_get();
    }

    /**
     * Get the authenticated user with all necessary relationships.
     * This method caches the user to prevent duplicate queries.
     */
    protected function getAuthUser()
    {
        if ($this->authUser === null) {
            $this->authUser = User::with([
                'roles',
                'employee',
                'shortcuts',
                // Load media with specific columns to avoid duplicate queries
                'media' => function($query) {
                    $query->select(['id', 'model_id', 'model_type', 'disk', 'collection_name', 'file_name']);
                },
                'employee_shifts' => function($query) {
                    $query->where('status', 'Active')
                          ->latest('created_at')
                          ->limit(1);
                }
            ])->find(auth()->id());

            // Preload the current_shift property to avoid n+1 queries
            if ($this->authUser && $this->authUser->relationLoaded('employee_shifts')) {
                $activeShift = $this->authUser->employee_shifts->first();
                if ($activeShift) {
                    // Store the active shift in a property for easy access
                    $this->authUser->active_shift = $activeShift;
                }
            }
        }

        return $this->authUser;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the authenticated user using our cached method
        $authUser = $this->getAuthUser();

        $userIds = $authUser->user_interactions->pluck('id');

        // Optimize user query by selecting only necessary columns
        // and using a join instead of loading all user_interactions
        $users = User::select(['users.id', 'users.name'])
                    ->whereIn('id', $userIds)
                    ->orWhere('users.id', $authUser->id)
                    ->whereStatus('Active')
                    ->distinct()
                    ->get();

        // Get attendances with optimized query
        $attendances = $this->getAttendancesQuery($request)
                            ->orderByDesc('clock_in')
                            ->get();

        $total = null;
        if ($request->user_id) {
            $attendanceService = new AttendanceService();

            // Don't load the full User model again if it's the auth user
            $userId = (int)$request->user_id;
            if ($userId === $authUser->id) {
                $user = $authUser;
            } else {
                $user = User::with([
                    'employee_shifts' => function($query) {
                        $query->where('status', 'Active')
                              ->latest('created_at')
                              ->limit(1);
                    },
                    'employee'
                ])
                ->select(['id', 'name'])
                ->findOrFail($userId);

                // Store the active shift in a property for easy access
                if ($user->relationLoaded('employee_shifts')) {
                    $activeShift = $user->employee_shifts->first();
                    if ($activeShift) {
                        $user->active_shift = $activeShift;
                    }
                }
            }

            $month = $request->created_month_year ? Carbon::parse($request->created_month_year) : Carbon::now()->format('Y-m-d');

            // Use optimized methods that don't require looping through attendances
            $total['regularWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Regular', $month);
            $total['overtimeWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Overtime', $month);
            $total['breakTime'] = $attendanceService->userTotalBreakTime($user, $month);
            $total['overBreakTime'] = $attendanceService->userTotalOverBreakTime($user, $month);
        }

        // Check if the user has already clocked in today
        $clockedIn = $this->getUserClockedInStatus($authUser->id);

        return view('administration.attendance.index', compact('users', 'attendances', 'clockedIn', 'total'));
    }

    /**
     * Display a listing of the authenticated user's attendances.
     */
    public function myAttendances(Request $request)
    {
        // Get the authenticated user using our cached method
        $user = $this->getAuthUser();

        $attendances = $this->getAttendancesQuery($request, $user->id)
                            ->latest()
                            ->get();

        $attendanceService = new AttendanceService();
        $month = $request->created_month_year ? Carbon::parse($request->created_month_year) : Carbon::now()->format('Y-m-d');

        // Use optimized methods that don't require looping through attendances
        $total['regularWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Regular', $month);
        $total['overtimeWorkedHours'] = $attendanceService->userTotalWorkingHour($user, 'Overtime', $month);
        $total['breakTime'] = $attendanceService->userTotalBreakTime($user, $month);
        $total['overBreakTime'] = $attendanceService->userTotalOverBreakTime($user, $month);

        // Check if the user has already clocked in today - use the user we already have
        $clockedIn = $this->getUserClockedInStatus($user->id);

        return view('administration.attendance.my', compact('attendances', 'clockedIn', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the authenticated user using our cached method
        $authUser = $this->getAuthUser();

        $users = User::select(['id', 'name'])
                        ->whereIn('id', $authUser->user_interactions->pluck('id'))
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
            toast('Clocked In and Out Successfully for ' . $user->alias_name . ' on ' . $request->clock_in_date . '.', 'success');
            return redirect()->back();

        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    // Clockin
    public function clockIn(Request $request)
    {
        // Get the authenticated user using our cached method
        $user = $this->getAuthUser();
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
        // Get the authenticated user using our cached method
        $user = $this->getAuthUser();

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
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();

            toast('Attendance Record deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * export attendances.
     */
    public function export(Request $request)
    {
        // Building the query based on filters with optimized select and joins
        $query = Attendance::select([
                'attendances.id', 'attendances.user_id', 'attendances.employee_shift_id',
                'attendances.clock_in_date', 'attendances.clock_in', 'attendances.clock_out',
                'attendances.total_time', 'attendances.total_adjusted_time', 'attendances.type',
                'attendances.clockin_medium',
                'attendances.clockout_medium',
                'attendances.clockin_scanner_id',
                'attendances.clockout_scanner_id',
                'attendances.ip_address',
                'attendances.country',
                'attendances.city',
                'attendances.zip_code',
                'attendances.time_zone',
                'attendances.latitude',
                'attendances.longitude',
                'attendances.created_at',
                'attendances.updated_at',
            ])
            ->with([
                'user:id,name',
                'employee_shift:id,start_time,end_time',
            ])
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->orderBy('users.name'); // Order by user name

        // Initialize variables for filename parts
        $userName = '';
        $monthYear = '';
        $clockinType = '';

        // Handle user_id filter
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('attendances.user_id', $request->user_id);
            $user = User::select('name')->find($request->user_id);
            $userName = $user ? '_of_' . strtolower(str_replace(' ', '_', $user->name)) : '';
        }

        // Handle created_month_year filter
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYearDate = Carbon::parse($request->created_month_year);
            $query->whereYear('attendances.clock_in', $monthYearDate->year)
                ->whereMonth('attendances.clock_in', $monthYearDate->month);
            $monthYear = '_of_' . $monthYearDate->format('m_Y');
        } else {
            if (!$request->has('filter_attendance')) {
                $query->whereBetween('attendances.clock_in_date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Handle type filter
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('attendances.type', $request->type);
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
        // Only select necessary columns and optimize eager loading
        $query = Attendance::select([
            'attendances.id', 'attendances.user_id', 'attendances.employee_shift_id',
            'attendances.clock_in_date', 'attendances.clock_in', 'attendances.clock_out',
            'attendances.total_time', 'attendances.total_adjusted_time', 'attendances.type'
        ]);

        // Load user with specific columns and nested relationships
        $query->with([
            'user' => function($query) {
                $query->select(['id', 'userid', 'name', 'first_name', 'last_name'])
                      ->with([
                          'employee:id,user_id,alias_name',
                          'roles:id,name'
                          // We're not loading media here to avoid duplicate queries
                          // Media is already loaded in getAuthUser() for the authenticated user
                      ]);
            },
            'employee_shift:id,start_time,end_time'
        ]);

        // Always load daily_breaks for the index view to prevent n+1 queries
        // Only select the necessary columns from daily_breaks
        $query->with(['daily_breaks' => function($query) {
            $query->select([
                'id', 'attendance_id', 'break_in_at', 'break_out_at',
                'total_time', 'over_break'
            ])->whereNotNull('break_out_at');
        }]);

        if ($userId) {
            $query->where('attendances.user_id', $userId);
        }

        // Filter by user_id if provided
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('attendances.user_id', $request->user_id);
        }

        // Filter by created month and year if provided
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::parse($request->created_month_year);
            $query->whereYear('attendances.clock_in', $monthYear->year)
                  ->whereMonth('attendances.clock_in', $monthYear->month);
        } else {
            // Apply default filter if no filter_attendance is set
            if (!$request->has('filter_attendance')) {
                $query->whereBetween('attendances.clock_in_date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Filter by type if provided
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('attendances.type', $request->type);
        }

        return $query;
    }

    /**
     * Check if the user has already clocked in today.
     * Only select necessary columns to reduce data transfer.
     */
    private function getUserClockedInStatus($userId)
    {
        return Attendance::select(['id', 'user_id', 'clock_in'])
                         ->where('user_id', $userId)
                         ->whereNull('clock_out')
                         ->first();
    }
}
