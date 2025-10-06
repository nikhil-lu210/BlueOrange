<?php

namespace App\Services\Administration\Translator;

use App\Models\Translation\Translation;
use Stichoza\GoogleTranslate\GoogleTranslate;


class TranslatorService
{
    protected $translator;
    protected $locale;

    public function __construct($locale = 'en')
    {
        $this->locale = $locale;
        $this->translator = new GoogleTranslate($locale);
    }

    public function translate($text)
    {
        // Normalize text (trim spaces, handle nulls)
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        // 1. Check if already cached in DB
        $cached = Translation::where('source_text', $text)
                             ->where('locale', $this->locale)
                             ->first();

        if ($cached) {
            return $cached->translated_text;
        }

        // 2. Translate using Google API
        $translated = $this->translator->translate($text);

        // 3. Save to DB for future use
        Translation::create([
            'source_text'     => $text,
            'locale'          => $this->locale,
            'translated_text' => $translated,
        ]);

        return $translated;
    }
}
