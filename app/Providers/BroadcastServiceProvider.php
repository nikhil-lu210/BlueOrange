<?php

namespace App\Providers;

// Removed Broadcast facade
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Broadcasting routes removed

        require base_path('routes/channels.php');
    }
}
