<?php

namespace App\Http\Controllers\Administration\LifeCycle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Intended routes:
     * - overview (GET /)
     * - overview.index (GET /overview)
     */
    public function index()
    {
        // Mock statistics data
        $stats = [
            'new_hires' => 12,
            'active_employees' => 247,
            'departures' => 3,
            'transfers' => 8
        ];

        // Mock recent activity data
        $recentActivity = [
            [
                'id' => 1,
                'employee_name' => 'Sarah Johnson',
                'employee_initials' => 'SJ',
                'position' => 'Software Developer',
                'department' => 'Engineering',
                'activity' => 'Onboarding',
                'status' => 'onboarding',
                'progress' => 65,
                'timestamp' => Carbon::now()->subHours(2),
                'color_class' => 'primary'
            ],
            [
                'id' => 2,
                'employee_name' => 'Mike Chen',
                'employee_initials' => 'MC',
                'position' => 'Marketing Manager',
                'department' => 'Marketing',
                'activity' => 'Active',
                'status' => 'active',
                'progress' => 100,
                'timestamp' => Carbon::now()->subHours(4),
                'color_class' => 'success'
            ],
            [
                'id' => 3,
                'employee_name' => 'Lisa Rodriguez',
                'employee_initials' => 'LR',
                'position' => 'HR Specialist',
                'department' => 'Human Resources',
                'activity' => 'Offboarding',
                'status' => 'offboarding',
                'progress' => 45,
                'timestamp' => Carbon::now()->subHours(6),
                'color_class' => 'warning'
            ],
            [
                'id' => 4,
                'employee_name' => 'James Wilson',
                'employee_initials' => 'JW',
                'position' => 'Sales Representative',
                'department' => 'Sales',
                'activity' => 'Transfer',
                'status' => 'transfer',
                'progress' => 80,
                'timestamp' => Carbon::now()->subDay(),
                'color_class' => 'info'
            ],
            [
                'id' => 5,
                'employee_name' => 'Emma Thompson',
                'employee_initials' => 'ET',
                'position' => 'Product Designer',
                'department' => 'Design',
                'activity' => 'Onboarding',
                'status' => 'onboarding',
                'progress' => 90,
                'timestamp' => Carbon::now()->subDays(2),
                'color_class' => 'primary'
            ]
        ];

        return view('administration.lifecycle.index', compact('stats', 'recentActivity'));
    }

    public function onboarding()
    {
        return view('administration.lifecycle.onboarding');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('overview.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and process request data as needed
        // $validated = $request->validate([...]);
        // ...

        return redirect()->route('overview.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('overview.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('overview.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate and update as needed
        // $validated = $request->validate([...]);
        // ...

        return redirect()->route('overview.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Handle deletion as needed
        // ...

        return redirect()->route('overview.index');
    }
}
