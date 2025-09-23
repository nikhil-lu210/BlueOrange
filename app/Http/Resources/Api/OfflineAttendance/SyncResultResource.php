<?php

namespace App\Http\Resources\Api\OfflineAttendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyncResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'synced_count' => $this['synced_count'],
            'total_count' => $this['total_count'],
            'synced_record_ids' => $this['synced_record_ids'],
            'errors' => $this['errors']
        ];
    }
}
