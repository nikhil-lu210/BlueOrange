<?php

namespace App\Http\Resources\Api\OfflineAttendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->resource['user_id'] ?? $this->user_id,
            'name' => $this->resource['name'] ?? $this->name,
            'email' => $this->resource['email'] ?? $this->email,
            'permissions' => $this->resource['permissions'] ?? $this->permissions ?? [],
        ];
    }
}
