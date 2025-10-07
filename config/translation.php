<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Translation Locales
    |--------------------------------------------------------------------------
    |
    | This array contains all the supported locales for the translation system.
    | Each locale has a shortcode, name, original name (in native language),
    | and a flag to indicate if it should be used in the application.
    |
    */

    'supported_locales' => [
        'en' => [
            'shortcode' => 'en',
            'name' => 'English',
            'original' => 'English',
            'will_use' => true,
        ],
        'bn' => [
            'shortcode' => 'bn',
            'name' => 'Bengali',
            'original' => 'বাংলা',
            'will_use' => true,
        ],
        'hi' => [
            'shortcode' => 'hi',
            'name' => 'Hindi',
            'original' => 'हिन्दी',
            'will_use' => true,
        ],
        'ar' => [
            'shortcode' => 'ar',
            'name' => 'Arabic',
            'original' => 'العربية',
            'will_use' => false,
        ],
        'es' => [
            'shortcode' => 'es',
            'name' => 'Spanish',
            'original' => 'Español',
            'will_use' => false,
        ],
        'fr' => [
            'shortcode' => 'fr',
            'name' => 'French',
            'original' => 'Français',
            'will_use' => false,
        ],
        'de' => [
            'shortcode' => 'de',
            'name' => 'German',
            'original' => 'Deutsch',
            'will_use' => false,
        ],
        'ja' => [
            'shortcode' => 'ja',
            'name' => 'Japanese',
            'original' => '日本語',
            'will_use' => false,
        ],
        'ko' => [
            'shortcode' => 'ko',
            'name' => 'Korean',
            'original' => '한국어',
            'will_use' => false,
        ],
        'zh' => [
            'shortcode' => 'zh',
            'name' => 'Chinese',
            'original' => '中文',
            'will_use' => false,
        ],
        'pt' => [
            'shortcode' => 'pt',
            'name' => 'Portuguese',
            'original' => 'Português',
            'will_use' => false,
        ],
        'ru' => [
            'shortcode' => 'ru',
            'name' => 'Russian',
            'original' => 'Русский',
            'will_use' => false,
        ],
        'it' => [
            'shortcode' => 'it',
            'name' => 'Italian',
            'original' => 'Italiano',
            'will_use' => false,
        ],
        'tr' => [
            'shortcode' => 'tr',
            'name' => 'Turkish',
            'original' => 'Türkçe',
            'will_use' => false,
        ],
        'ur' => [
            'shortcode' => 'ur',
            'name' => 'Urdu',
            'original' => 'اردو',
            'will_use' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Source Language
    |--------------------------------------------------------------------------
    |
    | The default source language for translations. This is typically English.
    |
    */

    'default_source_language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Translation Character Limits
    |--------------------------------------------------------------------------
    |
    | Maximum character limits for source and translated text.
    |
    */

    'character_limits' => [
        'source_text' => 5000,
        'translated_text' => 10000,
    ],

];

