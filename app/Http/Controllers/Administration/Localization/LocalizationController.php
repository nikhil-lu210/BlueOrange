<?php

namespace App\Http\Controllers\Administration\Localization;

use App\Http\Controllers\Controller;

class LocalizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($lang)
    {
        // Get the list of valid keys from the config
        $validKeys = array_column(config('localization.languages'), 'key');

        // Check if the $lang is in the list of valid keys
        if (!in_array($lang, $validKeys)) {
            abort(400);
        }

        // Set local language
        session(['localization' => $lang]);

        return redirect()->back();
    }
}
