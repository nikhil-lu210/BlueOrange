<?php

namespace App\Http\Resources\Api\OfflineAttendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyncResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'synced_count' => $this->resource['synced_count'] ?? $this->synced_count ?? 0,
            'total_count' => $this->resource['total_count'] ?? $this->total_count ?? 0,
            'synced_record_ids' => $this->resource['synced_record_ids'] ?? $this->synced_record_ids ?? [],
            'errors' => $this->resource['errors'] ?? $this->errors ?? [],
            'success' => $this->resource['success'] ?? $this->success ?? false,
        ];
    }
}
