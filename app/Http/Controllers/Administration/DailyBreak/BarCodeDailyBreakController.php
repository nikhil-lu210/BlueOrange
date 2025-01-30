<?php

namespace App\Http\Controllers\Administration\DailyBreak;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DailyBreak\DailyBreak;
use App\Services\Administration\DailyBreak\BreakStartStopService;

class BarCodeDailyBreakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function scanner()
    {
        $scanner_id = auth()->user()->userid;

        // Get the start and end of today
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        // Query to get breaks created today
        $breaks = DailyBreak::with([
            'user:id,userid,name', 
            'user.media', 
            'user.roles', 
            'attendance'
        ])
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->orderByDesc('break_in_at')
        ->get();

        return view('administration.daily_break.barcode_scanner', compact(['scanner_id', 'breaks']));
    }

    public function scanBarCode(Request $request, $scanner_id, BreakStartStopService $breakStartStopService)
    {
        if ($scanner_id != auth()->user()->userid) {
            toast('You are not authorized to scan code.', 'warning');
            return redirect()->back();
        }

        $user = User::where('userid', $request->input('userid'))->firstOrFail();

        $currentTime = now();
        $currentDate = $currentTime->toDateString();

        // Retrieve the existing Break record
        $existingBreak = DailyBreak::where('user_id', $user->id)->whereNull('break_out_at')->first();
        // dd($request->all(), $existingBreak->type);

        try {
            if (!$existingBreak) {
                $breakStartStopService->startBreak($user, $request);
                toast($user->employee->alias_name.'\'s '.$request->break_type.' Break Has Been Started Successfully.', 'success');
            } else {
                $breakStartStopService->stopBreak($user);
                toast($user->employee->alias_name.'\'s '.$existingBreak->type.' Break Has Been Stopped Successfully.', 'success');
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
