<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and active
        if (Auth::check() && Auth::user()->status !== 'Active') {
            // Log the user out if they are not active
            Auth::logout();

            // Redirect them to the login page with a message
            toast('Your account is not active. Please contact support.', 'warning');
            return redirect()->route('login')->withErrors([
                'userid' => 'Your account is not active. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
