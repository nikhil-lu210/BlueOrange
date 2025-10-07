<?php

namespace App\Services\Administration\Translator;

use App\Models\Translation\Translation;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslatorService
{
    protected GoogleTranslate $translator;

    protected string $locale;

    public function __construct(string $locale = 'en')
    {
        $this->locale = $locale;
        $this->translator = new GoogleTranslate($locale);
    }

    public function translate(string $text): string
    {
        // Normalize text (trim spaces, handle nulls)
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        // Skip translation if target locale is English
        if ($this->locale === 'en') {
            return $text;
        }

        // Validate locale format (basic validation)
        if (! $this->isValidLocale($this->locale)) {
            Log::warning('Invalid locale provided for translation', [
                'locale' => $this->locale,
                'text' => $text,
            ]);

            return $text;
        }

        // Check text length limit (Google Translate has limits)
        if (strlen($text) > 5000) {
            Log::warning('Text too long for translation', [
                'text_length' => strlen($text),
                'locale' => $this->locale,
            ]);

            return $text;
        }

        // 1. Check if already cached in DB
        $cached = Translation::where('source_text', $text)
            ->where('locale', $this->locale)
            ->first();

        if ($cached) {
            return $cached->translated_text;
        }

        try {
            // 2. Translate using Google API
            $translated = $this->translator->translate($text);

            // 3. Save to DB for future use (only if not English)
            if ($this->locale !== 'en') {
                Translation::create([
                    'source_text' => $text,
                    'locale' => $this->locale,
                    'translated_text' => $translated,
                ]);
            }

            return $translated;
        } catch (\Exception $e) {
            Log::error('Translation failed', [
                'text' => $text,
                'locale' => $this->locale,
                'error' => $e->getMessage(),
            ]);

            // Return original text if translation fails
            return $text;
        }
    }

    /**
     * Validate if the locale format is acceptable
     */
    private function isValidLocale(string $locale): bool
    {
        // Basic validation - should be 2-5 characters, lowercase
        return preg_match('/^[a-z]{2,5}$/', $locale) === 1;
    }

    /**
     * Get supported locales from config
     */
    public static function getSupportedLocales(): array
    {
        $locales = config('translation.supported_locales', []);
        $result = [];

        foreach ($locales as $code => $locale) {
            $result[$code] = $locale['name'];
        }

        return $result;
    }

    /**
     * Get active supported locales (only those with will_use = true)
     */
    public static function getActiveLocales(): array
    {
        $locales = config('translation.supported_locales', []);
        $result = [];

        foreach ($locales as $code => $locale) {
            if ($locale['will_use']) {
                $result[$code] = $locale['name'];
            }
        }

        return $result;
    }

    /**
     * Get detailed locale information
     */
    public static function getLocaleDetails(string $code): ?array
    {
        return config("translation.supported_locales.{$code}");
    }

    /**
     * Get all locale details (full information)
     */
    public static function getAllLocaleDetails(): array
    {
        return config('translation.supported_locales', []);
    }
}
