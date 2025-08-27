<?php

namespace App\Services\Administration\Inventory;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FileMedia\FileMedia;
use App\Models\Inventory\Inventory;
use App\Models\Inventory\InventoryCategory;

class InventoryService
{
    /**
     * Store inventory items with files and descriptions
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function storeInventory(Request $request): void
    {
        DB::transaction(function () use ($request) {
            // Handle category creation if it's a new category
            $categoryId = $this->handleCategoryCreation($request);

            // Get common settings
            $commonSettings = $this->getCommonSettings($request);

            // Store common files once if needed
            $commonFilePaths = [];
            if ($commonSettings['common_files'] && $request->hasFile('common_files')) {
                $commonFilePaths = $this->storeCommonFilesOnce($request);
            }

            // Create inventory items based on quantity
            $items = $request->input('items', []);

            foreach ($items as $itemIndex => $itemData) {
                $this->createInventoryItem($request, $categoryId, $commonSettings, $itemIndex, $itemData, $commonFilePaths);
            }
        });
    }

    /**
     * Handle category creation if it's a new category
     *
     * @param Request $request
     * @return int
     */
    private function handleCategoryCreation(Request $request): int
    {
        $categoryId = $request->category_id;

        if ($request->has('category_name') && !empty($request->category_name)) {
            $category = InventoryCategory::create([
                'name' => $request->category_name,
                'creator_id' => auth()->id(),
            ]);
            $categoryId = $category->id;
        }

        return $categoryId;
    }

    /**
     * Get common settings from request
     *
     * @param Request $request
     * @return array
     */
    private function getCommonSettings(Request $request): array
    {
        // Fix checkbox detection - check if common_files exists in request
        $commonFiles = $request->has('common_files');

        // Fix: Properly detect common_description checkbox
        // When checkbox is unchecked, it's not sent in request at all
        // So if it exists in request, it's checked; if not, it's unchecked
        $commonDescription = $request->has('common_description');

        return [
            'common_files' => $commonFiles,
            'common_description' => $commonDescription,
            'common_description_text' => $request->input('common_description_input'),
        ];
    }

    /**
     * Store common files once and return file paths
     *
     * @param Request $request
     * @return array
     */
    private function storeCommonFilesOnce(Request $request): array
    {
        $commonFilePaths = [];
        $commonFilesArray = $request->file('common_files');

        if (is_array($commonFilesArray)) {
            foreach ($commonFilesArray as $file) {
                if ($file && $file->isValid()) {
                    try {
                        // Store file in a common directory
                        $directory = 'inventory/common_files';
                        $path = $file->store($directory);

                        $commonFilePaths[] = [
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'original_name' => $file->getClientOriginalName(),
                        ];
                    } catch (Exception $e) {
                        throw new Exception('Common file upload error: ' . $e->getMessage());
                    }
                }
            }
        }

        return $commonFilePaths;
    }

    /**
     * Create a single inventory item with files and description
     *
     * @param Request $request
     * @param int $categoryId
     * @param array $commonSettings
     * @param int $itemIndex
     * @param array $itemData
     * @param array $commonFilePaths
     * @return void
     */
    private function createInventoryItem(Request $request, int $categoryId, array $commonSettings, int $itemIndex, array $itemData, array $commonFilePaths = []): void
    {
        // Determine description based on common description setting
        $description = $this->getItemDescription($commonSettings, $itemData);

        // Create inventory record
        $inventory = Inventory::create([
            'category_id' => $categoryId,
            'creator_id' => auth()->id(),
            'name' => $request->name,
            'unique_number' => $itemData['unique_number'] ?? null,
            'price' => $itemData['price'] ?? null,
            'description' => $description,
            'usage_for' => $request->usage_for,
            'status' => 'Available',
        ]);

        // Handle file uploads
        $this->handleFileUploads($request, $inventory, $commonSettings, $itemIndex, $itemData, $commonFilePaths);
    }

    /**
     * Get the appropriate description for an inventory item
     *
     * @param array $commonSettings
     * @param array $itemData
     * @return string|null
     */
    private function getItemDescription(array $commonSettings, array $itemData): ?string
    {
        if ($commonSettings['common_description']) {
            return $commonSettings['common_description_text'];
        }

        // Fix: Return individual description when common description is unchecked
        $individualDescription = $itemData['description'] ?? null;

        return $individualDescription;
    }

    /**
     * Handle file uploads for inventory item
     *
     * @param Request $request
     * @param Inventory $inventory
     * @param array $commonSettings
     * @param int $itemIndex
     * @param array $itemData
     * @param array $commonFilePaths
     * @return void
     */
    private function handleFileUploads(Request $request, Inventory $inventory, array $commonSettings, int $itemIndex, array $itemData, array $commonFilePaths = []): void
    {
        // Handle common files (create file media records with existing file paths)
        if ($commonSettings['common_files'] && !empty($commonFilePaths)) {
            $this->createCommonFileRecords($inventory, $commonFilePaths);
        }

        // Handle individual files
        if (!$commonSettings['common_files'] && isset($itemData['files']) && is_array($itemData['files'])) {
            $this->uploadIndividualFiles($itemData['files'], $inventory, 'inventory/' . $inventory->id);
        }
    }

    /**
     * Create file media records for common files (using existing file paths)
     *
     * @param Inventory $inventory
     * @param array $commonFilePaths
     * @return void
     */
    private function createCommonFileRecords(Inventory $inventory, array $commonFilePaths): void
    {
        foreach ($commonFilePaths as $fileData) {
            try {
                // Create a new file media record with the existing file path
                $fileMedia = new FileMedia([
                    'file_name' => $fileData['file_name'],
                    'file_path' => $fileData['file_path'], // Same path as stored file
                    'mime_type' => $fileData['mime_type'],
                    'file_size' => $fileData['file_size'],
                    'original_name' => $fileData['original_name'],
                    'note' => 'Common inventory files',
                ]);

                $inventory->files()->save($fileMedia);
            } catch (Exception $e) {
                throw new Exception('Common file record creation error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Upload individual files for inventory item
     *
     * @param array $files
     * @param Inventory $inventory
     * @param string $directory
     * @return void
     */
    private function uploadIndividualFiles(array $files, Inventory $inventory, string $directory): void
    {
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                try {
                    store_file_media($file, $inventory, $directory, 'Individual inventory files');
                } catch (Exception $e) {
                    throw new Exception('Individual file upload error: ' . $e->getMessage());
                }
            }
        }
    }
}
