<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Administration\User\UserService;

class UserAdvanceFiterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter data and users based on filters
        $data = $this->userService->getAdvancedFilterData($request);

        return view('administration.settings.user.advance_filter', $data);
    }
}
