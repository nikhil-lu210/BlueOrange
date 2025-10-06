<?php

namespace App\Services\Administration\LearningHub;

use App\Models\LearningHub\LearningHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class LearningHubService
{
    /**
     * Create a new learning topic
     *
     * @param array $data
     * @return LearningHub
     * @throws Exception
     */
    public function createLearningTopic(array $data): LearningHub
    {
        $learningTopic = null;

        DB::transaction(function () use ($data, &$learningTopic) {
            // Process recipients
            $recipients = $this->processRecipients($data['recipients'] ?? null);

            // Create the learning topic
            $learningTopic = LearningHub::create([
                'creator_id' => auth()->id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'recipients' => $recipients,
            ]);

            // Store Learning Topic Files
            if (isset($data['files']) && is_array($data['files'])) {
                $this->uploadFiles($learningTopic, $data['files']);
            }

            // Notifications and emails will be handled by the observer
        });

        // Load the creator relationship for the observer
        if ($learningTopic) {
            $learningTopic->load('creator');
        }

        if (!$learningTopic) {
            throw new Exception('Failed to create learning topic');
        }

        return $learningTopic;
    }

    /**
     * Update an existing learning topic
     *
     * @param LearningHub $learningTopic
     * @param array $data
     * @return LearningHub
     * @throws Exception
     */
    public function updateLearningTopic(LearningHub $learningTopic, array $data): LearningHub
    {
        DB::transaction(function () use ($learningTopic, $data) {
            // Process recipients
            $recipients = $this->processRecipients($data['recipients'] ?? null);

            $learningTopic->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'recipients' => $recipients,
            ]);

            // Handle file updates if needed
            if (isset($data['files']) && is_array($data['files'])) {
                $this->uploadFiles($learningTopic, $data['files']);
            }
        });

        return $learningTopic;
    }

    /**
     * Delete a learning topic
     *
     * @param LearningHub $learningTopic
     * @return bool
     * @throws Exception
     */
    public function deleteLearningTopic(LearningHub $learningTopic): bool
    {
        try {
            return $learningTopic->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete learning topic: ' . $e->getMessage());
        }
    }

    /**
     * Process recipients data
     *
     * @param array|null $recipients
     * @return array|null
     */
        private function processRecipients(?array $recipients): ?array
    {
        if (!$recipients) {
            return null;
        }

        return collect($recipients)
            ->flatten()
            ->filter(fn($value) => $value !== 'selectAllValues' && is_numeric($value))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Upload files helper method
     *
     * @param LearningHub $learningTopic
     * @param array $files
     * @return void
     */
    private function uploadFiles(LearningHub $learningTopic, array $files): void
    {
        foreach ($files as $file) {
            $directory = 'learning_hub/' . $learningTopic->id;
            store_file_media($file, $learningTopic, $directory);
        }
    }




}
