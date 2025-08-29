<?php

namespace App\Http\Requests\Administration\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'category_id' => 'required_without:category_name|exists:inventory_categories,id',
            'category_name' => 'required_without:category_id|string|max:255',
            'unique_number' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'usage_for' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Available,In Use,Out of Service,Damaged',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Inventory name is required.',
            'name.max' => 'Inventory name cannot exceed 255 characters.',
            'category_id.required_without' => 'Please select a category or enter a new category name.',
            'category_id.exists' => 'The selected category is invalid.',
            'category_name.required_without' => 'Please select a category or enter a new category name.',
            'category_name.max' => 'Category name cannot exceed 255 characters.',
            'unique_number.max' => 'Unique number cannot exceed 255 characters.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'usage_for.required' => 'Usage purpose is required.',
            'usage_for.max' => 'Usage purpose cannot exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Please select a valid status.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.mimes' => 'Files must be of type: jpeg, png, jpg, gif, svg, webp.',
            'files.*.max' => 'Each file cannot exceed 10MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle new category creation
        if ($this->has('category_id') && !empty($this->category_id)) {
            // If category_id is not numeric, it means it's a new category name
            if (!is_numeric($this->category_id)) {
                $this->merge([
                    'category_name' => $this->category_id,
                    'category_id' => null
                ]);
            }
        }

        // Handle new purpose creation
        if ($this->has('usage_for') && !empty($this->usage_for)) {
            // If usage_for is not in the existing purposes, it's a new one
            // We'll let the service handle this logic
        }
    }
}
