<?php

use App\Services\Administration\Translator\TranslatorService;
use Illuminate\Support\Facades\Log;

if (! function_exists('___')) {
    /**
     * Translate the given sentence using Google Translate
     *
     * @param  string  $sentence  The text to translate
     * @param  string|null  $target  The target language code (optional)
     * @return string The translated text or original text if translation fails
     */
    function ___(string $sentence, ?string $target = null): string
    {
        try {
            // Get locale from session, fallback to default
            $targetLanguage = $target ?? session('localization', config('app.locale', 'en'));

            // Skip translation if target is English or same as source
            if ($targetLanguage === 'en') {
                return $sentence;
            }

            // Translate using TranslatorService
            $translator = new TranslatorService($targetLanguage);

            return $translator->translate($sentence);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Translation helper failed', [
                'sentence' => $sentence,
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            // If translation fails, return original sentence
            return $sentence;
        }
    }
}
