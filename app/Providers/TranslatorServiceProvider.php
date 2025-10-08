<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Administration\Translator\CustomTranslator;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerCustomTranslator();
    }

    /**
     * Register the custom translator with Google Translate fallback.
     */
    private function registerCustomTranslator(): void
    {
        $this->app->extend('translator', function ($translator, $app) {
            $customTranslator = new CustomTranslator(
                $app['translation.loader'],
                $app['config']['app.locale']
            );

            $customTranslator->setFallback($app['config']['app.fallback_locale']);

            return $customTranslator;
        });
    }
}
