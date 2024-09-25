<?php

namespace App\Http\Controllers\Administration\DailyBreak;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use Stevebauman\Location\Facades\Location;
use App\Exports\Administration\DailyBreak\DailyBreakExport;

class DailyBreakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::select(['id', 'name'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->get();

        $query = DailyBreak::with([
                                'user:id,userid,name', 
                                'user.media', 
                                'user.roles'
                            ])
                            ->orderByDesc('break_in_at');

        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            if (!$request->has('filter_breaks')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        $dailyBreaks = $query->get();
                                
        return view('administration.daily_break.index', compact(['users', 'dailyBreaks']));
    }

    /**
     * Display a listing of the resource.
     */
    public function myDailyBreaks(Request $request)
    {
        $query = DailyBreak::with([
                                'user:id,userid,name', 
                                'user.media', 
                                'user.roles'
                            ])
                            ->whereUserId(auth()->user()->id)
                            ->orderByDesc('break_in_at');

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            if (!$request->has('filter_breaks')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        $dailyBreaks = $query->get();
                                
        return view('administration.daily_break.my', compact(['dailyBreaks']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $currentDate = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $currentDate)
            ->whereType('Regular')
            ->first();

        $breaks = DailyBreak::where('user_id', $user->id)->where('date', $currentDate)->get();

        $activeBreak = DailyBreak::where('user_id', $user->id)->whereNull('break_out_at')->whereNull('total_time')->first();
        
        return view('administration.daily_break.create', compact(['attendance', 'breaks', 'activeBreak']));
    }


    /**
     * Start break
     */
    public function startBreak(Request $request)
    {
        $user = auth()->user();
        $currentTime = Carbon::now(); // current time as Carbon instance
        $currentDate = $currentTime->toDateString(); // Get the current date in 'Y-m-d' format

        // Ensure that the authenticated user is taking a break for their own account
        abort_if($request->userid != $user->userid, 403, 'You are not authorized to take the Break. Please take your break from your account.');

        // Check if the user has an open attendance for today (clocked in as Regular but not clocked out)
        $attendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $currentDate)
            ->whereType('Regular') // Only considering regular clock-ins
            ->whereNull('clock_out') // Must not be clocked out yet
            ->first();

        // If no active attendance is found, the user cannot take a break
        if (!$attendance) {
            toast('You cannot take any break as you have not clocked in today or already clocked out.', 'warning');
            return redirect()->back();
        }

        // Get the user's current shift to apply time constraints
        $shift = $user->current_shift;
        if ($shift) {
            $shiftStartTime = Carbon::parse($shift->start_time); // Convert shift start time to Carbon instance
            $shiftEndTime = Carbon::parse($shift->end_time); // Convert shift end time to Carbon instance

            // Ensure the user is not taking a break in the first or last hour of the shift
            if (
                $currentTime->between($shiftStartTime, $shiftStartTime->copy()->addHour()) || // First hour of the shift
                $currentTime->between($shiftEndTime->copy()->subHour(), $shiftEndTime) // Last hour of the shift
            ) {
                toast('You cannot take a break in the first or last hour of your shift.', 'warning');
                return redirect()->back();
            }
        }

        // Count the number of short and long breaks taken for this attendance
        $shortBreakCount = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->where('type', 'Short')
            ->count();

        $longBreakCount = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->where('type', 'Long')
            ->count();

        // Restrict short breaks to 2 per attendance
        if ($request->break_type == 'Short' && $shortBreakCount >= 2) {
            toast('You have already taken 2 Short Breaks today.', 'warning');
            return redirect()->back();
        }

        // Restrict long breaks to 1 per attendance
        if ($request->break_type == 'Long' && $longBreakCount >= 1) {
            toast('You have already taken your Long Break today.', 'warning');
            return redirect()->back();
        }

        // Get the most recent break taken by the user for this attendance
        $lastBreak = DailyBreak::where('user_id', $user->id)
            ->where('attendance_id', $attendance->id)
            ->orderBy('break_in_at', 'desc')
            ->first();

        // Ensure that at least 1 hour has passed since the user's last break
        if ($lastBreak && $lastBreak->break_in_at && $currentTime->diffInHours(Carbon::parse($lastBreak->break_in_at)) < 1) {
            toast('You cannot take another break within 1 hour of your previous break.', 'warning');
            return redirect()->back();
        }

        // Retrieve the user's current IP location (if available)
        $location = Location::get(get_public_ip());

        try {
            // Start a database transaction to safely insert the new break data
            DB::transaction(function() use ($user, $attendance, $currentTime, $location, $currentDate, $request) {
                DailyBreak::create([
                    'user_id' => $user->id,
                    'attendance_id' => $attendance->id,
                    'date' => $currentDate,
                    'break_in_at' => $currentTime, // Record the current break start time
                    'break_in_ip' => $location->ip ?? 'N/A', // Use IP or 'N/A' if not available
                    'type' => $request->break_type, // Break type ('Short' or 'Long')
                ]);
            }, 5); // Retry up to 5 times in case of deadlock

            // Show a success message after the transaction is completed
            toast('Break Started Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            // Handle any exception that occurs during the database transaction
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * stop break
     */
    public function stopBreak(Request $request)
    {
        $user = auth()->user();
        $currentTime = now();
        // dd($request->all());

        // Retrieve the existing Break record
        $existingBreak = DailyBreak::where('user_id', $user->id)
            ->whereNull('break_out_at')
            ->first();

        if (!$existingBreak) {
            toast('You have no running break.', 'warning');
            return redirect()->back();
        }

        $location = Location::get(get_public_ip());

        try {
            $breakStopTime = $currentTime->timestamp; // Use timestamp
            $breakStartTime = $existingBreak->break_in_at->timestamp;

            // Calculate total time in seconds
            $totalSeconds = $breakStopTime - $breakStartTime;

            // Convert total time to HH:MM:SS format
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            $formattedTotalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            DB::transaction(function() use ($existingBreak, $location, $breakStopTime, $formattedTotalTime) {
                // Update the existing break record
                $updateSuccess = $existingBreak->update([
                    'break_out_at' => $breakStopTime,
                    'total_time' => $formattedTotalTime,
                    'break_out_ip' => $location->ip ?? 'N/A',
                ]);
            
                // Check if the update was successful
                if ($updateSuccess) {
                    // Now calculate the overbreak and update the record
                    $existingBreak->over_break = over_break($existingBreak->id);
                    $existingBreak->save(); // Save the updated over_break value
                }
            });            

            toast('Break Stopped Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * export daily_breaks.
     */
    public function export(Request $request)
    {
        // Building the query based on filters
        $query = DailyBreak::with([
            'user:id,name',
        ])
        ->whereHas('user')
        ->orderBy(User::select('name')->whereColumn('users.id', 'daily_breaks.user_id')); // order by asc

        // Initialize variables for filename parts
        $userName = '';
        $monthYear = '';
        $breakInType = '';
        
        // Handle user_id filter
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            $userName = $user ? '_of_' . strtolower(str_replace(' ', '_', $user->name)) : '';
        }

        // Handle created_month_year filter
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYearDate = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYearDate->year)
                ->whereMonth('date', $monthYearDate->month);
            $monthYear = '_of_' . $monthYearDate->format('m_Y');
        } else {
            if (!$request->has('filter_breaks')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }
        
        // Handle type filter
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
            
            $breakInType = strtolower($request->type). '_';
        }

        // Get the filtered breaks
        $breaks = $query->get();

        if ($breaks->count() < 1) {
            toast('There is no daily breaks to download.', 'warning');
            return redirect()->back();
        }

        $downloadMonth = $monthYear ? $monthYear : '_'.date('m_Y');
        $fileName = $breakInType . 'daily_breaks_backup_of_' . $userName . $downloadMonth . '.xlsx';

        // Return the Excel download with the appropriate filename
        return Excel::download(new DailyBreakExport($breaks), $fileName);
    }
}
