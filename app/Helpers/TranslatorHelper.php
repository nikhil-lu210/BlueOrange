<?php

use App\Services\Administration\Translator\TranslatorService;

if (! function_exists('___')) {
    /**
     * Translate the given sentence using Google Translate
     *
     * @param string $sentence
     * @param string|null $target
     * @return string
     */

    function ___($sentence, $target = null)
    {
        try {
            // Get locale from session, fallback to default
            $targetLanguage = $target ?? session('localization', config('app.locale', 'en'));

            // Translate using TranslatorService
            $translator = new TranslatorService($targetLanguage);

            return $translator->translate($sentence);
        } catch (\Exception $e) {
            // If translation fails, return original sentence
            return $sentence;
        }
    }
}
