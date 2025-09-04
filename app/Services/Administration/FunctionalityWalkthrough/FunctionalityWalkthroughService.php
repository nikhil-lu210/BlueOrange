<?php

namespace App\Services\Administration\FunctionalityWalkthrough;

use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthroughStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FunctionalityWalkthroughService
{
    /**
     * Create a new functionality walkthrough
     *
     * @param array $data
     * @return FunctionalityWalkthrough
     * @throws Exception
     */
    public function createWalkthrough(array $data): FunctionalityWalkthrough
    {
        $walkthrough = null;

        DB::transaction(function () use ($data, &$walkthrough) {
            // Process assigned roles
            $assignedRoles = $this->processAssignedRoles($data['assigned_roles'] ?? null);

            // Create the walkthrough
            $walkthrough = FunctionalityWalkthrough::create([
                'creator_id' => auth()->id(),
                'title' => $data['title'],
                'assigned_roles' => $assignedRoles,
            ]);

            // Store walkthrough steps
            if (isset($data['steps']) && is_array($data['steps'])) {
                $this->createSteps($walkthrough, $data['steps']);
            }

            // Store walkthrough files
            if (isset($data['files']) && is_array($data['files'])) {
                $this->uploadFiles($walkthrough, $data['files']);
            }

            // Notifications and emails will be handled by the observer
        });

        // Load the creator relationship for the observer
        if ($walkthrough) {
            $walkthrough->load('creator');
        }

        if (!$walkthrough) {
            throw new Exception('Failed to create functionality walkthrough');
        }

        return $walkthrough;
    }

    /**
     * Update an existing functionality walkthrough
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @param array $data
     * @return FunctionalityWalkthrough
     * @throws Exception
     */
    public function updateWalkthrough(FunctionalityWalkthrough $walkthrough, array $data): FunctionalityWalkthrough
    {
        DB::transaction(function () use ($walkthrough, $data) {
            // Process assigned roles
            $assignedRoles = $this->processAssignedRoles($data['assigned_roles'] ?? null);

            $walkthrough->update([
                'title' => $data['title'],
                'assigned_roles' => $assignedRoles,
            ]);

            // Delete existing steps
            $walkthrough->steps()->delete();

            // Create updated steps
            if (isset($data['steps']) && is_array($data['steps'])) {
                $this->createSteps($walkthrough, $data['steps']);
            }
        });

        return $walkthrough;
    }

    /**
     * Delete a functionality walkthrough
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @return bool
     * @throws Exception
     */
    public function deleteWalkthrough(FunctionalityWalkthrough $walkthrough): bool
    {
        try {
            return $walkthrough->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete functionality walkthrough: ' . $e->getMessage());
        }
    }

    /**
     * Get roles for dropdown
     */
    public function getRolesForDropdown()
    {
        return \Spatie\Permission\Models\Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();
    }

    /**
     * Get walkthroughs for index page
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWalkthroughsForIndex(Request $request)
    {
        $query = FunctionalityWalkthrough::withCreatorDetails()
            ->byCreator($request->creator_id)
            ->byMonthYear($request->created_month_year)
            ->latest();

        return $query->get();
    }

    /**
     * Get walkthroughs for authenticated user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWalkthroughsForUser()
    {
        return FunctionalityWalkthrough::withCreatorDetails()
            ->get()
            ->filter(fn($walkthrough) => $walkthrough->isAuthorized());
    }

    /**
     * Get walkthrough with full relationships
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @return FunctionalityWalkthrough
     */
    public function getWalkthroughWithRelations(FunctionalityWalkthrough $walkthrough): FunctionalityWalkthrough
    {
        return $walkthrough->load([
            'creator.employee',
            'creator.media',
            'files',
            'steps.files'
        ]);
    }

    /**
     * Update read status for user
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @param int $userId
     * @return void
     */
    public function markAsRead(FunctionalityWalkthrough $walkthrough, int $userId): void
    {
        $walkthrough->updateReadByAt($userId);
    }

    /**
     * Create steps for walkthrough
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @param array $stepsData
     * @return void
     */
    private function createSteps(FunctionalityWalkthrough $walkthrough, array $stepsData): void
    {
        foreach ($stepsData as $index => $step) {
            if (!empty($step['step_title']) && !empty($step['step_description'])) {
                $walkthroughStep = FunctionalityWalkthroughStep::create([
                    'walkthrough_id' => $walkthrough->id,
                    'step_title' => $step['step_title'],
                    'step_description' => $step['step_description'],
                    'step_order' => $index + 1,
                ]);

                // Store step files if any
                if (isset($step['files']) && is_array($step['files'])) {
                    $this->uploadStepFiles($walkthroughStep, $step['files']);
                }
            }
        }
    }

    /**
     * Upload files for walkthrough
     *
     * @param FunctionalityWalkthrough $walkthrough
     * @param array $files
     * @return void
     */
    private function uploadFiles(FunctionalityWalkthrough $walkthrough, array $files): void
    {
        foreach ($files as $file) {
            $directory = 'functionality_walkthroughs/' . $walkthrough->id;
            store_file_media($file, $walkthrough, $directory);
        }
    }

    /**
     * Upload files for walkthrough step
     *
     * @param FunctionalityWalkthroughStep $step
     * @param array $files
     * @return void
     */
    private function uploadStepFiles(FunctionalityWalkthroughStep $step, array $files): void
    {
        foreach ($files as $file) {
            $directory = 'walkthrough_steps/' . $step->id;
            store_file_media($file, $step, $directory);
        }
    }

    /**
     * Process assigned roles data
     *
     * @param array|null $assignedRoles
     * @return array|null
     */
    private function processAssignedRoles(?array $assignedRoles): ?array
    {
        if (!$assignedRoles) {
            return null;
        }

        return collect($assignedRoles)
            ->flatten()
            ->filter(fn($value) => $value !== 'selectAllValues' && is_numeric($value))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }
}
