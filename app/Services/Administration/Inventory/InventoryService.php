<?php

namespace App\Services\Administration\Inventory;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\FileMedia\FileMedia;
use App\Models\Inventory\Inventory;
use App\Models\Inventory\InventoryCategory;
use Spatie\Permission\Models\Role;

class InventoryService
{
    /**
     * Store multiple inventory items with files and descriptions
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function storeInventory(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $categoryId = $this->resolveCategoryId($request);
            $formSettings = $this->extractFormSettings($request);
            $commonFiles = $this->processCommonFilesIfNeeded($request, $formSettings);

            $this->createInventoryItems($request, $categoryId, $formSettings, $commonFiles);
        });
    }

    /**
     * Resolve category ID (create new category if needed)
     *
     * @param Request $request
     * @return int
     */
    private function resolveCategoryId(Request $request): int
    {
        $categoryId = $request->category_id;

        if ($this->isNewCategory($request)) {
            $category = $this->createNewCategory($request);
            $categoryId = $category->id;
        }

        return $categoryId;
    }

    /**
     * Check if request contains a new category
     *
     * @param Request $request
     * @return bool
     */
    private function isNewCategory(Request $request): bool
    {
        return $request->has('category_name') && !empty($request->category_name);
    }

    /**
     * Create a new inventory category
     *
     * @param Request $request
     * @return InventoryCategory
     */
    private function createNewCategory(Request $request): InventoryCategory
    {
        return InventoryCategory::create([
            'name' => $request->category_name,
            'creator_id' => auth()->id(),
        ]);
    }

    /**
     * Extract form settings from request
     *
     * @param Request $request
     * @return array
     */
    private function extractFormSettings(Request $request): array
    {
        return [
            'use_common_files' => $request->has('common_files'),
            'use_common_description' => $request->has('common_description'),
            'common_description_text' => $request->input('common_description_input'),
        ];
    }

    /**
     * Process common files if needed and return file data
     *
     * @param Request $request
     * @param array $formSettings
     * @return array
     */
    private function processCommonFilesIfNeeded(Request $request, array $formSettings): array
    {
        if (!$formSettings['use_common_files'] || !$request->hasFile('common_files')) {
            return [];
        }

        return $this->storeCommonFiles($request);
    }

    /**
     * Store common files once and return file metadata
     *
     * @param Request $request
     * @return array
     */
    private function storeCommonFiles(Request $request): array
    {
        $fileMetadata = [];
        $uploadedFiles = $request->file('common_files');

        if (!is_array($uploadedFiles)) {
            return $fileMetadata;
        }

        foreach ($uploadedFiles as $file) {
            if ($this->isValidFile($file)) {
                $fileMetadata[] = $this->storeSingleFile($file);
            }
        }

        return $fileMetadata;
    }

    /**
     * Check if file is valid
     *
     * @param mixed $file
     * @return bool
     */
    private function isValidFile($file): bool
    {
        return $file && $file->isValid();
    }

    /**
     * Store a single file and return its metadata
     *
     * @param mixed $file
     * @return array
     * @throws Exception
     */
    private function storeSingleFile($file): array
    {
        try {
            $directory = 'inventory/common_files';
            $path = $file->store($directory);

            return [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ];
        } catch (Exception $e) {
            throw new Exception('File upload error: ' . $e->getMessage());
        }
    }

    /**
     * Create all inventory items
     *
     * @param Request $request
     * @param int $categoryId
     * @param array $formSettings
     * @param array $commonFiles
     * @return void
     */
    private function createInventoryItems(Request $request, int $categoryId, array $formSettings, array $commonFiles): void
    {
        $items = $request->input('items', []);

        foreach ($items as $itemIndex => $itemData) {
            $this->createSingleInventoryItem($request, $categoryId, $formSettings, $itemIndex, $itemData, $commonFiles);
        }
    }

    /**
     * Create a single inventory item
     *
     * @param Request $request
     * @param int $categoryId
     * @param array $formSettings
     * @param int $itemIndex
     * @param array $itemData
     * @param array $commonFiles
     * @return void
     */
    private function createSingleInventoryItem(Request $request, int $categoryId, array $formSettings, int $itemIndex, array $itemData, array $commonFiles): void
    {
        $description = $this->determineItemDescription($formSettings, $itemData);

        $inventory = $this->createInventoryRecord($request, $categoryId, $itemData, $description);

        $this->handleItemFiles($inventory, $formSettings, $itemData, $commonFiles);
    }

    /**
     * Determine the appropriate description for an inventory item
     *
     * @param array $formSettings
     * @param array $itemData
     * @return string|null
     */
    private function determineItemDescription(array $formSettings, array $itemData): ?string
    {
        if ($formSettings['use_common_description']) {
            return $formSettings['common_description_text'];
        }

        return $itemData['description'] ?? null;
    }

    /**
     * Create inventory database record
     *
     * @param Request $request
     * @param int $categoryId
     * @param array $itemData
     * @param string|null $description
     * @return Inventory
     */
    private function createInventoryRecord(Request $request, int $categoryId, array $itemData, ?string $description): Inventory
    {
        return Inventory::create([
            'category_id' => $categoryId,
            'creator_id' => auth()->id(),
            'name' => $request->name,
            'unique_number' => $itemData['unique_number'] ?? null,
            'price' => $itemData['price'] ?? null,
            'description' => $description,
            'usage_for' => $request->usage_for,
            'status' => 'Available',
        ]);
    }

    /**
     * Handle file processing for inventory item
     *
     * @param Inventory $inventory
     * @param array $formSettings
     * @param array $itemData
     * @param array $commonFiles
     * @return void
     */
    private function handleItemFiles(Inventory $inventory, array $formSettings, array $itemData, array $commonFiles): void
    {
        if ($formSettings['use_common_files'] && !empty($commonFiles)) {
            $this->linkCommonFilesToInventory($inventory, $commonFiles);
        }

        if (!$formSettings['use_common_files'] && $this->hasIndividualFiles($itemData)) {
            $this->uploadIndividualFiles($itemData['files'], $inventory);
        }
    }

    /**
     * Check if item has individual files
     *
     * @param array $itemData
     * @return bool
     */
    private function hasIndividualFiles(array $itemData): bool
    {
        return isset($itemData['files']) && is_array($itemData['files']);
    }

    /**
     * Link common files to inventory item (create file media records)
     *
     * @param Inventory $inventory
     * @param array $commonFiles
     * @return void
     */
    private function linkCommonFilesToInventory(Inventory $inventory, array $commonFiles): void
    {
        foreach ($commonFiles as $fileData) {
            $this->createFileMediaRecord($inventory, $fileData, 'Common inventory files');
        }
    }

    /**
     * Upload individual files for inventory item
     *
     * @param array $files
     * @param Inventory $inventory
     * @return void
     */
    private function uploadIndividualFiles(array $files, Inventory $inventory): void
    {
        $directory = 'inventory/' . $inventory->id;

        foreach ($files as $file) {
            if ($this->isValidFile($file)) {
                try {
                    store_file_media($file, $inventory, $directory, 'Individual inventory files');
                } catch (Exception $e) {
                    throw new Exception('Individual file upload error: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Create a file media record
     *
     * @param Inventory $inventory
     * @param array $fileData
     * @param string $note
     * @return void
     * @throws Exception
     */
    private function createFileMediaRecord(Inventory $inventory, array $fileData, string $note): void
    {
        try {
            $fileMedia = new FileMedia([
                'file_name' => $fileData['file_name'],
                'file_path' => $fileData['file_path'],
                'mime_type' => $fileData['mime_type'],
                'file_size' => $fileData['file_size'],
                'original_name' => $fileData['original_name'],
                'note' => $note,
            ]);

            $inventory->files()->save($fileMedia);
        } catch (Exception $e) {
            throw new Exception('File media record creation error: ' . $e->getMessage());
        }
    }

    /**
     * Update a single inventory item
     *
     * @param Request $request
     * @param Inventory $inventory
     * @return void
     * @throws Exception
     */
    public function updateInventory(Request $request, Inventory $inventory): void
    {
        DB::transaction(function () use ($request, $inventory) {
            $categoryId = $this->resolveCategoryIdForUpdate($request);
            $usageFor = $this->resolveUsageFor($request);

            $this->updateInventoryRecord($inventory, $request, $categoryId, $usageFor);
            $this->handleNewFileUploads($request, $inventory);
        });
    }

    /**
     * Resolve category ID for update (create new category if needed)
     *
     * @param Request $request
     * @return int
     */
    private function resolveCategoryIdForUpdate(Request $request): int
    {
        $categoryId = $request->category_id;

        if ($this->isNewCategory($request)) {
            $category = $this->createNewCategory($request);
            $categoryId = $category->id;
        }

        return $categoryId;
    }

    /**
     * Resolve usage purpose (handle new purpose creation)
     *
     * @param Request $request
     * @return string
     */
    private function resolveUsageFor(Request $request): string
    {
        $usageFor = $request->usage_for;

        // If usage_for starts with 'new:', it's a new purpose
        if (str_starts_with($usageFor, 'new:')) {
            return substr($usageFor, 4); // Remove 'new:' prefix
        }

        return $usageFor;
    }

    /**
     * Update inventory database record
     *
     * @param Inventory $inventory
     * @param Request $request
     * @param int $categoryId
     * @param string $usageFor
     * @return void
     */
    private function updateInventoryRecord(Inventory $inventory, Request $request, int $categoryId, string $usageFor): void
    {
        $inventory->update([
            'category_id' => $categoryId,
            'name' => $request->name,
            'unique_number' => $request->unique_number,
            'price' => $request->price,
            'description' => $request->description,
            'usage_for' => $usageFor,
            'status' => $request->status,
        ]);
    }

    /**
     * Handle new file uploads for inventory
     *
     * @param Request $request
     * @param Inventory $inventory
     * @return void
     */
    private function handleNewFileUploads(Request $request, Inventory $inventory): void
    {
        if ($request->hasFile('files')) {
            $directory = 'inventory/' . $inventory->id;
            $uploadedFiles = $request->file('files');

            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    if ($this->isValidFile($file)) {
                        try {
                            store_file_media($file, $inventory, $directory, 'Inventory files');
                        } catch (Exception $e) {
                            throw new Exception('File upload error: ' . $e->getMessage());
                        }
                    }
                }
            }
        }
    }

    /**
     * Update inventory status
     *
     * @param Inventory $inventory
     * @param string $status
     * @return void
     */
    public function updateInventoryStatus(Inventory $inventory, string $status): void
    {
        $inventory->update(['status' => $status]);
    }

    /**
     * Delete inventory and its associated files
     *
     * @param Inventory $inventory
     * @return void
     * @throws Exception
     */
    public function deleteInventory(Inventory $inventory): void
    {
        DB::transaction(function () use ($inventory) {
            // Load files
            $inventory->load('files');

            foreach ($inventory->files as $file) {
                try {
                    $isCommon = $file->note === 'Common inventory files';

                    // For common files, check if other inventories still use it
                    if ($isCommon) {
                        $otherInventoriesUsingFile = $file->inventories()
                            ->where('inventories.id', '!=', $inventory->id)
                            ->exists();
                        if ($otherInventoriesUsingFile) {
                            continue; // skip deletion
                        }
                    }

                    // Determine disk
                    $disk = Storage::disk('public')->exists($file->file_path) ? 'public' : 'local';

                    // Delete file from public disk if exists
                    if (Storage::disk($disk)->exists($file->file_path)) {
                        Storage::disk($disk)->delete($file->file_path);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error deleting inventory file: ' . $e->getMessage());
                }
            }

            // Delete the inventory (will also delete file_media records via relationship if cascading)
            $inventory->delete();
        });
    }

    /**
     * Get filtered inventory query with relationships
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredInventoryQuery(Request $request)
    {
        $query = Inventory::with(['category', 'creator.employee'])
            ->orderByDesc('created_at');

        $this->applyFilters($query, $request);

        return $query;
    }

    /**
     * Get categories for filter dropdown
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesForFilter()
    {
        return InventoryCategory::select(['id', 'name'])->get();
    }

    /**
     * Get unique usage purposes for filter dropdown
     *
     * @return array
     */
    public function getPurposesForFilter(): array
    {
        return Inventory::query()->distinct()->pluck('usage_for')->filter()->toArray();
    }

    /**
     * Get roles with users who have Inventory Create permission
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesWithInventoryCreators()
    {
        return Role::select(['id', 'name'])
            ->with([
                'users' => function ($user) {
                    $user->with(['employee'])->permission('Inventory Create')
                        ->select(['id', 'name'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active');
                }
            ])
            ->whereHas('users', function ($user) {
                $user->permission('Inventory Create');
            })
            ->distinct()
            ->get();
    }

    /**
     * Apply filters to inventory query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->has('category_id') && !is_null($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('usage_for') && !is_null($request->usage_for)) {
            $query->where('usage_for', $request->usage_for);
        }

        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Available');
        }

        if ($request->has('creator_id') && !is_null($request->creator_id)) {
            $query->where('creator_id', $request->creator_id);
        }
    }
}
