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
use App\Exports\Administration\DailyBreak\DailyBreakExport;
use App\Services\Administration\DailyBreak\BreakExportService;
use App\Services\Administration\DailyBreak\BreakStartStopService;

class DailyBreakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        // Eager load all necessary relationships
        $users = User::with(['roles', 'media', 'shortcuts', 'employee'])
                        ->whereIn('id', $userIds)
                        ->whereStatus('Active')
                        ->get(['id', 'name']);

        // Get daily breaks with the pre-loaded users
        $dailyBreaks = $this->getDailyBreaksQuery($request)
                            ->whereIn('user_id', $userIds)
                            ->get();

        return view('administration.daily_break.index', compact(['users', 'dailyBreaks']));
    }


    /**
     * Display a listing of the user's daily breaks.
     */
    public function myDailyBreaks(Request $request)
    {
        $dailyBreaks = $this->getDailyBreaksQuery($request, auth()->user()->id)->get();
                                    
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
    public function startBreak(Request $request, BreakStartStopService $breakStartStopService)
    {
        $user = auth()->user();

        // Ensure that the authenticated user is taking a break for their own account
        abort_if($request->userid != $user->userid, 403, 'You are not authorized to take the Break. Please take your break from your account.');

        try {
            $breakStartStopService->startBreak($user, $request);
            toast('Break Started Successfully.', 'success');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
        }

        return redirect()->back();
    }


    /**
     * stop break
     */
    public function stopBreak(Request $request, BreakStartStopService $breakStartStopService)
    {
        $user = auth()->user();

        try {
            $breakStartStopService->stopBreak($user);
            toast('Break Stopped Successfully.', 'success');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     */
    public function show(DailyBreak $break)
    {
        // dd($break);
        return view('administration.daily_break.show', compact(['break']));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyBreak $break)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'break_in_at' => 'required|date_format:H:i', // Validate time input in H:i format
            'break_out_at' => 'nullable|date_format:H:i', // Validate time input in H:i format (optional)
            'type' => 'required|string|in:Short,Long', // Ensure type is either Short or Long
        ]);

        // Combine the input time (H:i) with today's date
        $todayDate = Carbon::now()->format('Y-m-d');

        // Parse the time inputs as full DateTime using today's date
        $breakInAt = Carbon::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $validatedData['break_in_at']);
        $breakOutAt = $validatedData['break_out_at'] ? Carbon::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $validatedData['break_out_at']) : null;

        // Initialize an array for the fields to update
        $updateData = [
            'break_in_at' => $breakInAt,
            'break_out_at' => $breakOutAt,
            'type' => $validatedData['type'],
        ];

        DB::transaction(function () use ($break, $updateData, $breakInAt, $breakOutAt) {
            // Calculate total time if break_out_at is provided
            if ($breakOutAt) {
                // Calculate total time in seconds
                $totalSeconds = $breakOutAt->diffInSeconds($breakInAt);

                // Convert total time to HH:MM:SS format
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
                $formattedTotalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                // Add total_time to the update data
                $updateData['total_time'] = $formattedTotalTime;
            } else {
                // Reset total_time if break_out_at is not provided
                $updateData['total_time'] = null;
            }

            // Update the break record with the prepared data
            $break->update($updateData);

            // Now calculate and update the over_break value
            $break->over_break = over_break($break->id); // Assuming this method exists to calculate over_break
            $break->save(); // Save the updated over_break value
        });

        // Flash a success message and redirect
        toast('Daily Break updated successfully.', 'success');
        return redirect()->back();
    }

    /**
     * export daily_breaks.
     */
    public function export(Request $request, BreakExportService $breakExportService)
    {
        try {
            $exportData = $breakExportService->exportBreaks($request);

            if (is_null($exportData)) {
                toast('There are no daily breaks to download.', 'warning');
                return redirect()->back();
            }

            // Return the Excel download with the appropriate filename
            return Excel::download(new DailyBreakExport($exportData['breaks']), $exportData['fileName']);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * Build the query for retrieving daily breaks.
     *
     * @param Request $request
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getDailyBreaksQuery(Request $request, int $userId = null)
    {
        $query = DailyBreak::with([
                                'user:id,userid,name', 
                                'user.media', 
                                'user.roles'
                            ])
                            ->orderByDesc('break_in_at');

        // Apply user ID filter if provided
        if ($userId) {
            $query->whereUserId($userId);
        }

        // Handle month/year filtering
        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            // Default to current month if no specific filter is applied
            if (!$request->has('filter_breaks')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        return $query;
    }
}
