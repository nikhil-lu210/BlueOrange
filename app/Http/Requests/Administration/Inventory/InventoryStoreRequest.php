<?php

namespace App\Http\Requests\Administration\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryStoreRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:5',
            'category_id' => 'required_without:category_name',
            'category_name' => 'required_without:category_id|string|max:255',
            'usage_for' => 'required|string|max:255',
            'common_files' => 'nullable',
            'common_description' => 'nullable',
            'common_files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:5120',
            'common_description_input' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
        ];

        // Add validation rules for each inventory item
        if ($this->has('items')) {
            foreach ($this->input('items') as $index => $item) {
                $rules["items.{$index}.unique_number"] = 'nullable|string|max:255';
                $rules["items.{$index}.price"] = 'nullable|numeric|min:0|max:999999.99';
                $rules["items.{$index}.files.*"] = 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:5120';
                $rules["items.{$index}.description"] = 'nullable|string|max:1000';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Inventory name is required.',
            'name.max' => 'Inventory name cannot exceed 255 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 5.',
            'category_id.required_without' => 'Please select a category or enter a new category name.',
            'category_name.required_without' => 'Please select a category or enter a new category name.',
            'category_name.max' => 'Category name cannot exceed 255 characters.',
            'usage_for.required' => 'Usage purpose is required.',
            'usage_for.max' => 'Usage purpose cannot exceed 255 characters.',
            'common_files.*.file' => 'Common files must be valid files.',
            'common_files.*.image' => 'Common files must be a valid image (jpg, jpeg, png, gif, bmp, svg, or webp).',
            'common_files.*.max' => 'Common files cannot exceed 5MB.',
            'common_description_input.max' => 'Common description cannot exceed 1000 characters.',
            'items.required' => 'At least one inventory item is required.',
            'items.array' => 'Inventory items must be in array format.',
            'items.min' => 'At least one inventory item is required.',
        ];
    }

    /**
     * Handle category creation if it's a new category
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        // Handle new category creation
        if (isset($data['category_id']) && str_starts_with($data['category_id'], 'new:')) {
            $categoryName = str_replace('new:', '', $data['category_id']);
            $data['category_name'] = $categoryName;
            $data['category_id'] = null;
        }

        // Handle new purpose creation
        if (isset($data['usage_for']) && str_starts_with($data['usage_for'], 'new:')) {
            $purposeName = str_replace('new:', '', $data['usage_for']);
            $data['usage_for'] = $purposeName;
        }

        // Handle checkbox values - convert to boolean
        if (isset($data['common_files'])) {
            $data['common_files'] = $data['common_files'] == '1' || $data['common_files'] === true;
        }
        if (isset($data['common_description'])) {
            $data['common_description'] = $data['common_description'] == '1' || $data['common_description'] === true;
        }

        $this->replace($data);
    }
}
