<?php

namespace App\Http\Controllers\Administration\Logs\LoginLogout;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User\LoginHistory;
use App\Http\Controllers\Controller;

class LoginLogoutHistoryController extends Controller
{
    public function index() {
        $histories = LoginHistory::with(['user'])
                                ->whereBetween('created_at', [
                                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                                    Carbon::now()->endOfMonth()->format('Y-m-d')
                                ])
                                ->get();
        
        return view('administration.logs.login_logout_history.index', compact(['histories']));
    }
}
