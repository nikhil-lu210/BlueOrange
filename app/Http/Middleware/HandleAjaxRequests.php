<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleAjaxRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If this is an AJAX request and we're getting a redirect response,
        // return a JSON response instead to prevent redirect loops
        if ($request->ajax() || $request->wantsJson()) {
            $response = $next($request);

            // If the response is a redirect and this is an AJAX request,
            // return a JSON response with the redirect URL
            if ($response->isRedirect()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => $response->getTargetUrl(),
                    'status' => 401
                ], 401);
            }

            return $response;
        }

        return $next($request);
    }
}
