<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CsrfController extends Controller
{
    /**
     * Refresh the CSRF token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Regenerate the CSRF token
        Session::regenerateToken();
        
        // Return the new token
        return response()->json([
            'token' => csrf_token(),
        ]);
    }
}
