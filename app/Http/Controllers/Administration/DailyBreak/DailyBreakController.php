<?php

namespace App\Http\Controllers\Administration\DailyBreak;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DailyBreak\DailyBreak;
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
