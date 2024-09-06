<?php

namespace App\Http\Controllers\Administration\DailyWorkUpdate;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\DailyWorkUpdate\DailyWorkUpdate;

class DailyWorkUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->getRolesWithPermission();

        $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request);

        return view('administration.daily_work_update.index', compact('roles', 'dailyWorkUpdates'));
    }

    /**
     * Display my work updates
     */
    public function my(Request $request)
    {
        $roles = $this->getRolesWithPermission();
        $authUserID = auth()->id();

        if (!$request->has('filter_work_updates') && auth()->user()->tl_employees_daily_work_updates->count() < 1) {
            $dailyWorkUpdates = DailyWorkUpdate::whereUserId($authUserID)
                                ->orWhere('team_leader_id', $authUserID)
                                ->get();
        } else {
            $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request, $authUserID);
        }

        return view('administration.daily_work_update.my', compact('roles', 'dailyWorkUpdates'));
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

    /**
     * Display the specified resource.
     */
    public function show(DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }



    

    /**
     * Helper method to get roles with 'Daily Work Update Create' permission
     */
    private function getRolesWithPermission()
    {
        return Role::select(['id', 'name'])
            ->with(['users' => function ($user) {
                $user->permission('Daily Work Update Create')->select(['id', 'name']);
            }])
            ->whereHas('users', function ($user) {
                $user->permission('Daily Work Update Create');
            })
            ->distinct()
            ->get();
    }

    /**
     * Helper method to filter Daily Work Updates
     */
    private function getFilteredDailyWorkUpdates(Request $request, $teamLeaderId = null)
    {
        $query = DailyWorkUpdate::query()->orderByDesc('created_at');

        if ($teamLeaderId) {
            $query->where('team_leader_id', $teamLeaderId);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('created_month_year')) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_work_updates')) {
            // Default to current month
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }

        return $query->get();
    }
}
