<?php

namespace App\Http\Resources\Api\OfflineAttendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'] ?? $this->id,
            'userid' => $this->resource['userid'] ?? $this->userid,
            'name' => $this->resource['name'] ?? $this->name,
            'alias_name' => $this->resource['alias_name'] ?? $this->alias_name ?? $this->name,
            'email' => $this->resource['email'] ?? $this->email,
        ];
    }
}
