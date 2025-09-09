<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CsrfController extends Controller
{
    /**
     * Refresh the CSRF token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'token' => csrf_token(),
                ], 401);
            }

            // Regenerate the CSRF token
            Session::regenerateToken();
            
            // Return the new token
            return response()->json([
                'success' => true,
                'token' => csrf_token(),
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error refreshing CSRF token: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh CSRF token',
                'token' => csrf_token(),
            ], 500);
        }
    }
}
