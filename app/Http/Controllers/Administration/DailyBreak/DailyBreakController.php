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

        $dailyBreaks = $query->get();

        
        $inBreak = DailyBreak::where('user_id', auth()->user()->id)
                                ->whereNull('break_out_at')
                                ->first();
                                
        return view('administration.daily_break.index', compact(['users', 'dailyBreaks', 'inBreak']));
    }


    /**
     * Start break
     */
    public function startBreak(Request $request)
    {
        $user = auth()->user();
        $currentTime = now();
        $currentDate = $currentTime->toDateString();

        // Check if the user has an open attendance of today (clocked in as Regular but not clocked out)
        $attendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $currentDate)
            ->whereType('Regular')
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            toast('You cannot take any break as you have not clocked in today or already clocked out.', 'warning');
            return redirect()->back();
        }

        $location = Location::get(get_public_ip());

        try {
            DB::transaction(function() use ($user, $attendance, $currentTime, $location, $currentDate) {
                DailyBreak::create([
                    'user_id' => $user->id,
                    'attendance_id' => $attendance->id,
                    'date' => $currentDate,
                    'break_in_at' => $currentTime,
                    'break_in_ip' => $location->ip ?? 'N/A',
                ]);
            }, 5);

            toast('Break Started Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
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

            $existingBreak->update([
                'break_out_at' => $breakStopTime,
                'total_time' => $formattedTotalTime,
                'break_out_ip' => $location->ip ?? 'N/A',
            ]);

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

        // Get the filtered breaks
        $breaks = $query->get();

        if ($breaks->count() < 1) {
            toast('There is no daily breaks to download.', 'warning');
            return redirect()->back();
        }

        $downloadMonth = $monthYear ? $monthYear : '_'.date('m_Y');
        $fileName = 'daily_breaks_backup_of_' . $userName . $downloadMonth . '.xlsx';

        // Return the Excel download with the appropriate filename
        return Excel::download(new DailyBreakExport($breaks), $fileName);
    }
}
