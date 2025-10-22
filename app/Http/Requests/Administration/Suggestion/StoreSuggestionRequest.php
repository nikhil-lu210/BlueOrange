<?php

namespace App\Http\Requests\Administration\Suggestion;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuggestionRequest extends FormRequest
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
        return [
            'type' => ['required', 'string', Rule::in(array_keys(config('feedback.types', [])))],
            'module' => ['required', 'string', Rule::in(array_keys(config('feedback.modules', [])))],
            'title' => 'required|string',
            'message' => 'required|string',
        ];
    }
}
