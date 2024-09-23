<?php

namespace App\Http\Controllers\Administration\DailyBreak;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\DailyBreak\DailyBreak;
use Illuminate\Http\Request;

class DailyBreakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dailyBreaks = DailyBreak::all();
        $attendance = Attendance::with(['daily_breaks'])->first();
        dd($attendance->total_breaks_taken, $attendance->total_break_time);
        return view('administration.daily_break.index', compact(['dailyBreaks']));
    }
}
