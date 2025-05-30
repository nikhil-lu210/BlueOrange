<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Administration\User\UserFilterService;

class UserAdvanceFiterController extends Controller
{
    protected $userFilterService;

    public function __construct(UserFilterService $userFilterService)
    {
        $this->userFilterService = $userFilterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter data and users based on filters
        $data = $this->userFilterService->getAdvancedFilterData($request);

        return view('administration.settings.user.advance_filter', $data);
    }
}
