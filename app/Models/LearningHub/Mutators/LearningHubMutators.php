<?php

namespace App\Models\LearningHub\Mutators;

trait LearningHubMutators
{
    /**
     * Ensure `read_by_at` is stored as JSON with consistent structure
     * [['read_by' => user_id, 'read_at' => timestamp], ...].
     * Supports arrays or objects (User model or ID).
     */
    public function setReadByAtAttribute($value): void
    {
        $items = collect($value)->map(function ($item) {
            return [
                'read_by' => is_object($item) ? $item->read_by->id ?? $item->read_by : $item['read_by'],
                'read_at' => is_object($item) ? $item->read_at : $item['read_at'],
            ];
        })->values()->all();

        $this->attributes['read_by_at'] = json_encode($items);
    }
}
