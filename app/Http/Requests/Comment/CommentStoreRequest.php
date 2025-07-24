<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'comment' => ['required', 'string', 'min:5'],
            'files.*' => ['nullable', 'max:5000'],
            'parent_comment_id' => [
                'nullable',
                'integer',
                'exists:comments,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Check if the parent comment is not a reply itself (prevent nested replies)
                        $parentComment = Comment::find($value);
                        if ($parentComment && $parentComment->parent_comment_id !== null) {
                            $fail('You can only reply to main comments, not to replies.');
                        }
                    }
                }
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'The comment is required.',
            'comment.min' => 'The comment must be at least 5 characters.',
            'parent_comment_id.exists' => 'The selected comment does not exist.'
        ];
    }
}
