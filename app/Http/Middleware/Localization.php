<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('localization', config('app.locale'));
        app()->setLocale($locale);

        view()->share('autoTranslator', new GoogleTranslate($locale));
        return $next($request);
    }
}
