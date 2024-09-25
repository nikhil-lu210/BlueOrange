<?php

namespace App\Http\Controllers\Administration\DailyBreak;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use App\Services\Administration\DailyBreak\BreakStartStopService;
use App\Exports\Administration\DailyBreak\DailyBreakExport;
use App\Services\Administration\DailyBreak\BreakExportService;

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

        $dailyBreaks = $this->getDailyBreaksQuery($request)->get();
                                        
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
