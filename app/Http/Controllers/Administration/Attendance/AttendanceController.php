<?php

namespace App\Http\Controllers\Administration\Attendance;

use App\Exports\Administration\Attendance\AttendanceExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;
use App\Http\Requests\Administration\Attendance\AttendanceUpdateRequest;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::select(['id', 'name'])->whereStatus('Active')->distinct()->get();

        $query = Attendance::with([
                                'user:id,userid,name', 
                                'user.media', 
                                'user.roles', 
                                'employee_shift:id,start_time,end_time'
                            ])
                            ->latest();

        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('clock_in', $monthYear->year)
                ->whereMonth('clock_in', $monthYear->month);
        }

        $attendances = $query->get();

        // Check if the user has already clocked in today
        $currentTime = now();
        $currentDate = $currentTime->toDateString();
        $clockedIn = Attendance::where('user_id', auth()->user()->id)
                                ->whereNull('clock_out')
                                ->first();

        return view('administration.attendance.index', compact('users', 'attendances', 'clockedIn'));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function myAttendances()
    {
        $attendances = Attendance::with(['user:id,name', 'employee_shift:id,start_time,end_time'])
                        ->where('user_id', auth()->user()->id)
                        ->latest()
                        ->distinct()
                        ->get();

        // Check if the user has already clocked in today
        $currentTime = now();
        $currentDate = $currentTime->toDateString();
        $clockedIn = Attendance::where('user_id', auth()->user()->id)
                                ->whereNull('clock_out')
                                ->first();

        return view('administration.attendance.my', compact('attendances', 'clockedIn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }
    
    // Clockin
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $currentTime = now();
        $currentDate = $currentTime->toDateString();

        // Check if the user has an open attendance session (clocked in but not clocked out)
        $openAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        if ($openAttendance) {
            toast('You have already clocked in and have not clocked out yet.', 'warning');
            return redirect()->back();
        }

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $currentDate)
            ->first();

        if ($existingAttendance) {
            toast('You have already clocked in today. Please click on Overtime-Clockin', 'warning');
            return redirect()->back();
        }

        $location = Location::get(get_public_ip());

        try {
            DB::transaction(function() use ($user, $currentTime, $location, $currentDate) {
                Attendance::create([
                    'user_id' => $user->id,
                    'employee_shift_id' => $user->current_shift->id,
                    'clock_in_date' => $currentDate,
                    'clock_in' => $currentTime,
                    'ip_address' => $location->ip ?? null,
                    'country' => $location->countryName ?? null,
                    'city' => $location->cityName ?? null,
                    'zip_code' => $location->zipCode ?? null,
                    'time_zone' => $location->timezone ?? null,
                    'latitude' => $location->latitude ?? null,
                    'longitude' => $location->longitude ?? null
                ]);
            }, 5);

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
        $userId = auth()->user()->id;
        $currentTime = now();

        // Retrieve the existing attendance record
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereNull('clock_out')
            ->first();

        if (!$existingAttendance) {
            toast('You have not clocked in today.', 'warning');
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
        // $attendance = $attendance->with('user')->firstOrFail();
        // dd($attendance);
        return view('administration.attendance.show', compact(['attendance']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        // dd($request->all(), $attendance);
        try {
            $attendance->clock_in = $request->clock_in;
            
            if ($request->clock_out) {
                $attendance->clock_out = $request->clock_out;
        
                // Calculate total time
                $clockInTime = Carbon::parse($request->clock_in);
                $clockOutTime = Carbon::parse($request->clock_out);
            
                // Check if clock in and clock out are on the same day
                $isSameDay = $clockInTime->isSameDay($clockOutTime);
            
                // Calculate total time with consideration for different dates
                if ($isSameDay) {
                    $totalTime = $clockInTime->diff($clockOutTime);
                } else {
                    $totalTime = $clockOutTime->diff($clockOutTime->copy()->startOfDay());
                }
            
                $formattedTotalTime = sprintf(
                    '%02d:%02d:%02d',
                    $totalTime->h, // total hours
                    $totalTime->i, // total minutes
                    $totalTime->s // total seconds
                );
            
                $attendance->total_time = $formattedTotalTime;
            }
        
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
        //
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
        ])->latest();

        // Initialize variables for filename parts
        $userName = '';
        $monthYear = '';
        
        // Handle user_id filter
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            $userName = $user ? 'of_' . strtolower(str_replace(' ', '_', $user->name)) : '';
        }

        // Handle created_month_year filter
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYearDate = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('clock_in', $monthYearDate->year)
                ->whereMonth('clock_in', $monthYearDate->month);
            $monthYear = 'of_' . $monthYearDate->format('m_Y');
        }

        // Get the filtered attendances
        $attendances = $query->get();

        if ($attendances->count() < 1) {
            toast('There is no attendances to download.', 'warning');
            return redirect()->back();
        }

        // Construct the filename based on conditions
        if (!empty($userName) && !empty($monthYear)) {
            $fileName = 'attendances_' . $userName . '_' . $monthYear . '.xlsx';
        } elseif (!empty($userName)) {
            $fileName = 'attendances_' . $userName . '.xlsx';
        } elseif (!empty($monthYear)) {
            $fileName = 'attendances_' . $monthYear . '.xlsx';
        } else {
            $date = now()->format('d_m_Y');
            $fileName = 'attendances_backup_' . $date . '.xlsx';
        }

        // Return the Excel download with the appropriate filename
        return Excel::download(new AttendanceExport($attendances), $fileName);
    }

}
