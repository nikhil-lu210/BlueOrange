<?php

namespace App\Http\Controllers\Administration\Settings\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAdvanceFiterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with(['employee', 'media', 'roles'])->whereStatus('Active')->get();

        return view('administration.settings.user.advance_filter', compact(['request', 'users']));
    }
}
