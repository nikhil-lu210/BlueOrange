<?php

namespace App\Services\Administration\Translator;

use App\Models\Translation\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslationManagementService
{
    /**
     * Get paginated translations
     */
    public function getPaginatedTranslations(int $perPage = 25, ?string $search = null, ?string $locale = null): LengthAwarePaginator
    {
        $query = Translation::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('source_text', 'LIKE', "%{$search}%")
                  ->orWhere('translated_text', 'LIKE', "%{$search}%");
            });
        }

        if ($locale) {
            $query->where('locale', $locale);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all translations
     */
    public function getAllTranslations(): Collection
    {
        return Translation::orderBy('locale', 'asc')
            ->orderBy('source_text', 'asc')
            ->get();
    }

    /**
     * Get translations grouped by locale
     */
    public function getTranslationsGroupedByLocale(): Collection
    {
        return Translation::orderBy('locale', 'asc')
            ->orderBy('source_text', 'asc')
            ->get()
            ->groupBy('locale');
    }

    /**
     * Create a new translation
     */
    public function createTranslation(array $data): Translation
    {
        try {
            DB::beginTransaction();

            $translation = Translation::create([
                'source_text' => $data['source_text'],
                'locale' => $data['locale'],
                'translated_text' => $data['translated_text'],
            ]);

            DB::commit();

            Log::info('Translation created', [
                'translation_id' => $translation->id,
                'locale' => $translation->locale,
                'user_id' => auth()->id(),
            ]);

            return $translation;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create translation', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            throw $e;
        }
    }

    /**
     * Update an existing translation
     */
    public function updateTranslation(Translation $translation, array $data): Translation
    {
        try {
            DB::beginTransaction();

            $translation->update([
                'source_text' => $data['source_text'],
                'locale' => $data['locale'],
                'translated_text' => $data['translated_text'],
            ]);

            DB::commit();

            Log::info('Translation updated', [
                'translation_id' => $translation->id,
                'locale' => $translation->locale,
                'user_id' => auth()->id(),
            ]);

            return $translation->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update translation', [
                'error' => $e->getMessage(),
                'translation_id' => $translation->id,
            ]);

            throw $e;
        }
    }

    /**
     * Delete a translation
     */
    public function deleteTranslation(Translation $translation): bool
    {
        try {
            DB::beginTransaction();

            $translationId = $translation->id;
            $translation->delete();

            DB::commit();

            Log::info('Translation deleted', [
                'translation_id' => $translationId,
                'user_id' => auth()->id(),
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete translation', [
                'error' => $e->getMessage(),
                'translation_id' => $translation->id,
            ]);

            throw $e;
        }
    }

    /**
     * Get supported locales
     */
    public function getSupportedLocales(): array
    {
        return TranslatorService::getSupportedLocales();
    }

    /**
     * Get active supported locales (only will_use = true)
     */
    public function getActiveLocales(): array
    {
        return TranslatorService::getActiveLocales();
    }

    /**
     * Get all locale details with full information
     */
    public function getAllLocaleDetails(): array
    {
        return TranslatorService::getAllLocaleDetails();
    }

    /**
     * Check if translation exists
     */
    public function translationExists(string $sourceText, string $locale, ?int $excludeId = null): bool
    {
        $query = Translation::where('source_text', $sourceText)
            ->where('locale', $locale);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get translation statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_translations' => Translation::count(),
            'translations_by_locale' => Translation::select('locale', DB::raw('count(*) as total'))
                ->groupBy('locale')
                ->pluck('total', 'locale')
                ->toArray(),
            'recent_translations' => Translation::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Bulk delete translations
     */
    public function bulkDelete(array $translationIds): int
    {
        try {
            DB::beginTransaction();

            $count = Translation::whereIn('id', $translationIds)->delete();

            DB::commit();

            Log::info('Bulk translation deletion', [
                'count' => $count,
                'user_id' => auth()->id(),
            ]);

            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk delete translations', [
                'error' => $e->getMessage(),
                'translation_ids' => $translationIds,
            ]);

            throw $e;
        }
    }

    /**
     * Search translations
     */
    public function searchTranslations(string $query, ?string $locale = null): Collection
    {
        $searchQuery = Translation::where(function ($q) use ($query) {
            $q->where('source_text', 'like', "%{$query}%")
                ->orWhere('translated_text', 'like', "%{$query}%");
        });

        if ($locale) {
            $searchQuery->where('locale', $locale);
        }

        return $searchQuery->orderBy('locale', 'asc')
            ->orderBy('source_text', 'asc')
            ->get();
    }
}

