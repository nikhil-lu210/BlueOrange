<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with(['employee'])->whereId(auth()->user()->id)->firstOrFail();
        // Pick a random birthday wish
        $wish = $this->randomBirthdayWish();
        
        // Get total worked days
        $totalWorkedDays = $this->calculateTotalWorkedDays($user);
        
        // Get total Regular worked days
        $totalRegularWork = $this->calculateTotalWork($user, 'Regular');
        
        // Get total Overtime worked days
        $totalOvertimeWork = $this->calculateTotalWork($user, 'Overtime');

        $activeAttendance = Attendance::select(['id', 'user_id', 'type', 'clock_in', 'clock_out'])
                                    ->whereUserId($user->id)
                                    ->whereNull('clock_out')
                                    ->latest()
                                    ->first();

        $attendances = Attendance::whereUserId($user->id)
                                    ->whereBetween('clock_in_date', [
                                        Carbon::now()->startOfMonth()->format('Y-m-d'),
                                        Carbon::now()->endOfMonth()->format('Y-m-d')
                                    ])
                                    ->orderByDesc('clock_in_date')
                                    ->orderByDesc('created_at')
                                    ->get();
        
        // dd($attendances);

        return view('administration.dashboard.index', compact([
            'user', 
            'wish', 
            'totalWorkedDays', 
            'totalRegularWork',
            'totalOvertimeWork',
            'activeAttendance',
            'attendances',
        ]));
    }



    // Method to calculate total worked days
    private function calculateTotalWorkedDays($user)
    {
        // Get the total distinct worked days from the attendances table
        return Attendance::where('user_id', $user->id)
            ->distinct('clock_in_date') // Get distinct clock_in_date to count unique days
            ->count('clock_in_date');
    }


    private function calculateTotalWork($user, $type)
    {
        // Fetch all the total_time values for type
        $totalTimes = Attendance::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNotNull('clock_out')
            ->pluck('total_time'); // Get all the total_time values

        // Initialize the total seconds variable
        $totalSeconds = 0;

        // Convert each total_time (in HH:MM:SS) to seconds and add it to totalSeconds
        foreach ($totalTimes as $time) {
            $totalSeconds += $this->timeToSeconds($time);
        }

        // Convert total seconds into HH:MM:SS format and return
        return $this->secondsToTimeFormat($totalSeconds);
    }

    private function timeToSeconds($time)
    {
        // Split the time into hours, minutes, and seconds
        list($hours, $minutes, $seconds) = explode(':', $time);

        // Convert the time to seconds
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    private function secondsToTimeFormat($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600); // Convert total seconds to hours
        $minutes = floor(($totalSeconds % 3600) / 60); // Remaining minutes
        $seconds = $totalSeconds % 60; // Remaining seconds
    
        // Format the time as HH:MM:SS
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    


    private function randomBirthdayWish()
    {
        // Predefined array of birthday wishes
        $birthdayWishes = [
            "May your birthday be the start of a year filled with good luck, good health, and much happiness.",
            "Enjoy your special day with all the people you love.",
            "Wishing you a day that’s as special as you are!",
            "This day is as special as you are. Your contributions to our team are invaluable, and your positive energy always brightens our day. Thank you for your hard work and dedication. May this year bring you even greater success and happiness. Enjoy your special day with loved ones and make some unforgettable memories. Cheers to another year of growth and achievement!",
            "May your birthday be a day filled with laughter, love, and all the things that make you happy. May the year ahead be filled with joy, success, and endless possibilities.",
            "Happy birthday to a truly exceptional person. You inspire us all with your kindness, intelligence, and unwavering spirit. May this day be as special as you are.",
            "As you celebrate another year of life, we want to express our sincere gratitude for your friendship and support. You make a positive impact on everyone around you, and we're lucky to have you in our lives. Happy birthday!",
            "May your birthday be a day filled with sunshine, laughter, and love. May the year ahead be filled with new adventures, exciting opportunities, and endless joy. Happy birthday!",
            "Happy birthday to a remarkable individual. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!",
            "Wishing you a birthday that is as bright and beautiful as you are. May your day be filled with joy, laughter, and love from all the people who care about you. Happy birthday!",
            "As you celebrate another year of life, we want to express our gratitude for your friendship and your contributions to our team. You are a valued member of our community, and we are lucky to have you. Happy birthday!",
            "May your birthday be a day filled with peace, love, and happiness. May the year ahead be filled with good health, prosperity, and success. Happy birthday!",
            "Happy birthday to a truly amazing person. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!"
        ];

        // Return a random birthday wish
        return $birthdayWishes[array_rand($birthdayWishes)];
    }
}
