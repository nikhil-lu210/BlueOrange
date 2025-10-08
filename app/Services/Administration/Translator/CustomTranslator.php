<?php

namespace App\Services\Administration\Translator;

use Illuminate\Translation\Translator;
use Illuminate\Support\Facades\Log;

class CustomTranslator extends Translator
{
    /**
     * Get the translation for a given key with Google Translate fallback.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        // First, try to get translation WITHOUT replacements to check if it exists
        $translationWithoutReplacements = parent::get($key, [], $locale, $fallback);

        // If translation was found in files (not same as key), apply replacements and return
        if ($translationWithoutReplacements !== $key) {
            // Translation exists in files, now get it with replacements
            return parent::get($key, $replace, $locale, $fallback);
        }

        // If key contains dots or colons, it's a translation key, not plain text
        // Don't auto-translate translation keys
        if (str_contains($key, '.') || str_contains($key, '::')) {
            return $key;
        }

        // Skip translation if target is English
        $targetLocale = $locale ?? $this->locale() ?? session('localization', config('app.locale', 'en'));

        if ($targetLocale === 'en') {
            return $this->makeReplacements($key, $replace);
        }

        // Use Google Translate for the missing translation
        try {
            $translator = new TranslatorService($targetLocale);
            $translated = $translator->translate($key);

            // Apply replacements if any
            if (! empty($replace)) {
                $translated = $this->makeReplacements($translated, $replace);
            }

            return $translated;
        } catch (\Exception $e) {
            Log::error('CustomTranslator: Google Translate fallback failed', [
                'key' => $key,
                'locale' => $targetLocale,
                'error' => $e->getMessage(),
            ]);

            // Return original key with replacements applied if translation fails
            return $this->makeReplacements($key, $replace);
        }
    }

    /**
     * Get a translation according to an integer value.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    public function choice($key, $number, array $replace = [], $locale = null)
    {
        // Try Laravel's choice translation first
        $translation = parent::choice($key, $number, $replace, $locale);

        // If translation was found (not same as key), return it
        if ($translation !== $key) {
            return $translation;
        }

        // For choice translations, if not found, just use the get method
        // which will trigger Google Translate if needed
        return $this->get($key, $replace, $locale);
    }
}

