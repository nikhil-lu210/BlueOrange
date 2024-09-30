<?php

namespace App\Listeners;

use App\Models\User\LoginHistory;
use Illuminate\Auth\Events\Login;
use Stevebauman\Location\Facades\Location;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $location = Location::get(get_public_ip());
        
        LoginHistory::create([
            'user_id' => $user->id,
            'login_time' => now(),
            'login_ip' => $location->ip ?? request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
