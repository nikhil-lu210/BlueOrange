<?php

namespace App\Listeners;

use App\Models\User\LoginHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Stevebauman\Location\Facades\Location;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogout
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
    public function handle(Logout $event)
    {
        $user = $event->user;
        $location = Location::get(get_public_ip());
        
        $lastLogin = LoginHistory::where('user_id', $user->id)
            ->orderBy('login_time', 'desc')
            ->first();

        if ($lastLogin && !$lastLogin->logout_time) {
            $lastLogin->update([
                'logout_ip' => $location->ip ?? request()->ip(),
                'logout_time' => now(),
            ]);
        }
    }
}
