<?php

namespace App\Services\Administration\Profile;

use App\Models\User;
use App\Models\Education\Institute\Institute;
use App\Models\Education\EducationLevel\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SelfProfileUpdateService
{
    /**
     * Update user's self-profile information.
     */
    public function updateInformation(User $user, Request $request): void
    {
        $validatedData = $this->validateRequest($request);
        $processedData = $this->processEducationalFields($validatedData);
        
        $user->employee()->update($processedData);
    }

    /**
     * Dynamically validate only the fields present in the request.
     */
    private function validateRequest(Request $request): array
    {
        $rules = $this->buildValidationRules($request);
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        return $validator->validated();
    }

    /**
     * Build validation rules based on fields present in the request.
     */
    private function buildValidationRules(Request $request): array
    {
        $rules = [];

        // Basic employee fields
        if ($request->has('blood_group')) {
            $rules['blood_group'] = 'required|string';
        }

        if ($request->has('father_name')) {
            $rules['father_name'] = 'required|string';
        }

        if ($request->has('mother_name')) {
            $rules['mother_name'] = 'required|string';
        }

        // Educational fields
        if ($request->has('institute_id')) {
            $rules['institute_id'] = $this->getInstituteValidationRule();
        }

        if ($request->has('education_level_id')) {
            $rules['education_level_id'] = $this->getEducationLevelValidationRule();
        }

        if ($request->has('passing_year')) {
            $rules['passing_year'] = $this->getPassingYearValidationRule();
        }

        return $rules;
    }

    /**
     * Get validation rule for institute field.
     */
    private function getInstituteValidationRule(): array
    {
        return [
            'nullable',
            function ($attribute, $value, $fail) {
                if (!$value) return;
                
                if (!str_starts_with($value, 'new:') && !is_numeric($value)) {
                    $fail('The institute must be a valid selection or new entry.');
                }
                
                if (is_numeric($value) && !Institute::where('id', $value)->exists()) {
                    $fail('The selected institute is invalid.');
                }
            }
        ];
    }

    /**
     * Get validation rule for education level field.
     */
    private function getEducationLevelValidationRule(): array
    {
        return [
            'nullable',
            function ($attribute, $value, $fail) {
                if (!$value) return;
                
                if (!str_starts_with($value, 'new:') && !is_numeric($value)) {
                    $fail('The education level must be a valid selection or new entry.');
                }
                
                if (is_numeric($value) && !EducationLevel::where('id', $value)->exists()) {
                    $fail('The selected education level is invalid.');
                }
            }
        ];
    }

    /**
     * Get validation rule for passing year field.
     */
    private function getPassingYearValidationRule(): array
    {
        return [
            'nullable',
            'integer',
            'min:1950',
            'max:' . (date('Y') + 10)
        ];
    }

    /**
     * Process educational fields and handle new entry creation.
     */
    private function processEducationalFields(array $validatedData): array
    {
        $processedData = $validatedData;

        // Handle new institute creation
        if (isset($validatedData['institute_id'])) {
            $processedData['institute_id'] = $this->handleInstituteCreation($validatedData['institute_id']);
        }

        // Handle new education level creation
        if (isset($validatedData['education_level_id'])) {
            $processedData['education_level_id'] = $this->handleEducationLevelCreation($validatedData['education_level_id']);
        }

        return $processedData;
    }

    /**
     * Handle institute creation for new entries.
     */
    private function handleInstituteCreation(?string $instituteValue): ?int
    {
        if (!$instituteValue) {
            return null;
        }

        // If it's a new institute (starts with 'new:')
        if (str_starts_with($instituteValue, 'new:')) {
            $instituteName = trim(substr($instituteValue, 4)); // Remove 'new:' prefix

            if (empty($instituteName)) {
                return null;
            }

            // Check if institute already exists
            $existingInstitute = Institute::where('name', $instituteName)->first();
            if ($existingInstitute) {
                return $existingInstitute->id;
            }

            // Create new institute (slug will be auto-generated by mutator)
            $institute = Institute::create([
                'name' => $instituteName,
                'description' => null,
            ]);

            return $institute->id;
        }

        // Return the existing institute ID
        return (int) $instituteValue;
    }

    /**
     * Handle education level creation for new entries.
     */
    private function handleEducationLevelCreation(?string $educationLevelValue): ?int
    {
        if (!$educationLevelValue) {
            return null;
        }

        // If it's a new education level (starts with 'new:')
        if (str_starts_with($educationLevelValue, 'new:')) {
            $educationLevelTitle = trim(substr($educationLevelValue, 4)); // Remove 'new:' prefix

            if (empty($educationLevelTitle)) {
                return null;
            }

            // Check if education level already exists
            $existingEducationLevel = EducationLevel::where('title', $educationLevelTitle)->first();
            if ($existingEducationLevel) {
                return $existingEducationLevel->id;
            }

            // Create new education level (slug will be auto-generated by mutator)
            $educationLevel = EducationLevel::create([
                'title' => $educationLevelTitle,
                'description' => null,
            ]);

            return $educationLevel->id;
        }

        // Return the existing education level ID
        return (int) $educationLevelValue;
    }
}
