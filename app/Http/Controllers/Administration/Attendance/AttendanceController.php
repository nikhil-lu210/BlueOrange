<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Stevebauman\Location\Facades\Location;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::orderBy('created_at', 'desc')->get();

        // Check if the user has already clocked in today
        $currentTime = now();
        $currentDate = $currentTime->toDateString();
        $clockedIn = Attendance::where('user_id', auth()->user()->id)
                                ->whereNull('clock_out')
                                ->exists();

        return view('administration.attendance.index', compact(['attendances', 'clockedIn']));
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
        $userId = auth()->user()->id;
        $currentTime = now();
        $currentDate = $currentTime->toDateString();

        // Check if the user has an open attendance session (clocked in but not clocked out)
        $openAttendance = Attendance::where('user_id', $userId)
            ->whereNull('clock_out')
            ->first();

        if ($openAttendance) {
            toast('You have already clocked in and have not clocked out yet.', 'warning');
            return redirect()->back();
        }

        $location = Location::get(get_public_ip());

        try {
            DB::transaction(function() use ($userId, $currentTime, $location, $currentDate) {
                Attendance::create([
                    'user_id' => $userId,
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
            $clockOutTime = $currentTime;
            $clockInTime = $existingAttendance->clock_in;

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
        //
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
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
