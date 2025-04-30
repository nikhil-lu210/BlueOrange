<?php

namespace App\Http\Controllers\Administration\Logs\LoginLogout;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User\LoginHistory;
use App\Http\Controllers\Controller;

class LoginLogoutHistoryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select(['id', 'name'])->whereStatus('Active')->orderBy('name')->get();

        $query = LoginHistory::with([
            'user:id,userid,name',
            'user.media',
            'user.roles'
        ])
        ->orderByDesc('created_at');

        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::parse($request->created_month_year);
            $query->whereYear('login_time', $monthYear->year)
                ->whereMonth('login_time', $monthYear->month);
        } else {
            // dd(Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d'));
            if (!$request->has('filter_attendance')) {
                $query->whereBetween('login_time', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        $histories = $query->get();

        return view('administration.logs.login_logout_history.index', compact(['users', 'histories']));
    }
}
