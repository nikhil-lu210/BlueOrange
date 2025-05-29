<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function login(User $user)
    {
        if (Auth::user()->hasRole(['Developer', 'Super Admin'])) {
            session(['impersonate' => Auth::id()]); // Store original user ID
            Auth::logout();
            Auth::login($user);

            toast('You are now logged in as ' . $user->alias_name, 'success');
            return redirect()->route('administration.dashboard.index');
        }

        alert('Unauthorized action.', 'error');
        return redirect()->back()->with('error', 'Unauthorized action.');
    }



    public function revert()
    {
        if (session()->has('impersonate')) {
            $originalUser = User::findOrFail(session('impersonate'));
            session()->forget('impersonate');
            Auth::logout();
            Auth::login($originalUser);

            toast('You are back as ' . $originalUser->alias_name, 'success');
            return redirect()->route('administration.dashboard.index');
        }

        alert('Unauthorized action.', 'error');
        return redirect()->back()->with('error', 'Unauthorized action.');
    }
}
