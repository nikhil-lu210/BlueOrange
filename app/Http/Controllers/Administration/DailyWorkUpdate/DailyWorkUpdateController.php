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
        $roles = Role::select(['id', 'name'])
                        ->with([
                            'users' => function ($user) {
                                $user->permission('Daily Work Update Create')->select(['id', 'name']);
                            }
                        ])
                        ->whereHas('users', function ($user) {
                            $user->permission('Daily Work Update Create');
                        })
                        ->distinct()
                        ->get();

        $query = DailyWorkUpdate::orderByDesc('created_at');

        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            // dd(Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d'));
            if (!$request->has('filter_work_updates')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        $dailyWorkUpdates = $query->get();

        return view('administration.daily_work_update.index', compact(['roles', 'dailyWorkUpdates']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        $roles = Role::select(['id', 'name'])
                        ->with([
                            'users' => function ($user) {
                                $user->permission('Daily Work Update Create')->select(['id', 'name']);
                            }
                        ])
                        ->whereHas('users', function ($user) {
                            $user->permission('Daily Work Update Create');
                        })
                        ->distinct()
                        ->get();

        $authUser = auth()->user()->id;

        if (!$request->has('filter_work_updates')) {
            $dailyWorkUpdates = DailyWorkUpdate::whereUserId($authUser)->orWhere('team_leader_id', $authUser)->get();
        } else {
            $query = DailyWorkUpdate::where('team_leader_id', $authUser)->orderByDesc('created_at');
    
            if ($request->has('user_id') && !is_null($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }
    
            if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
                $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
                $query->whereYear('date', $monthYear->year)
                    ->whereMonth('date', $monthYear->month);
            }
    
            $dailyWorkUpdates = $query->get();
        }

        // dd($dailyWorkUpdates, auth()->user()->tl_employees_daily_work_updates->count());

        return view('administration.daily_work_update.my', compact(['roles', 'dailyWorkUpdates']));
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
}
