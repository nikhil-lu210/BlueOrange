<?php

namespace App\Http\Requests\Administration\Settings\System\AppSetting\Translation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\Administration\Translator\TranslatorService;

class TranslationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $supportedLocales = array_keys(TranslatorService::getActiveLocales());
        $translationId = $this->route('translation')->id;
        $sourceTextLimit = config('translation.character_limits.source_text', 5000);
        $translatedTextLimit = config('translation.character_limits.translated_text', 10000);

        return [
            'source_text' => [
                'required',
                'string',
                "max:{$sourceTextLimit}",
                Rule::unique('translations', 'source_text')
                    ->where('locale', $this->input('locale'))
                    ->ignore($translationId),
            ],
            'locale' => [
                'required',
                'string',
                Rule::in($supportedLocales),
            ],
            'translated_text' => [
                'required',
                'string',
                "max:{$translatedTextLimit}",
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $sourceTextLimit = config('translation.character_limits.source_text', 5000);
        $translatedTextLimit = config('translation.character_limits.translated_text', 10000);

        return [
            'source_text.required' => 'The source text is required.',
            'source_text.string' => 'The source text must be a valid text.',
            'source_text.max' => "The source text may not be greater than {$sourceTextLimit} characters.",
            'source_text.unique' => 'This source text already has a translation for the selected locale.',
            'locale.required' => 'The locale is required.',
            'locale.in' => 'The selected locale is not supported.',
            'translated_text.required' => 'The translated text is required.',
            'translated_text.string' => 'The translated text must be a valid text.',
            'translated_text.max' => "The translated text may not be greater than {$translatedTextLimit} characters.",
        ];
    }
}
