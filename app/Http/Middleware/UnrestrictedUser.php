<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings\Settings;
use Symfony\Component\HttpFoundation\Response;

class UnrestrictedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is unrestricted
        if ($this->isUnrestrictedUser(auth()->user())) {
            // Set an attribute to mark the user as unrestricted
            $request->attributes->set('unrestricted_user', true);
        }

        return $next($request);
    }

    private function isUnrestrictedUser($user): bool
    {
        if (!$user) {
            return false;
        }

        $unrestrictedUsers = Settings::where('key', 'unrestricted_users')->value('value');
        $unrestrictedUsers = json_decode($unrestrictedUsers, true) ?? [];

        return collect($unrestrictedUsers)->pluck('user_id')->contains($user->id);
    }
}
