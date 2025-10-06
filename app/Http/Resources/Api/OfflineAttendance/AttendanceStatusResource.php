<?php

namespace App\Http\Resources\Api\OfflineAttendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceStatusResource extends JsonResource
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
            'userid' => $this->resource['userid'] ?? $this->userid,
            'has_open_attendance' => $this->resource['has_open_attendance'] ?? $this->has_open_attendance ?? false,
            'open_attendance_id' => $this->resource['open_attendance_id'] ?? $this->open_attendance_id,
            'clock_in_time' => $this->resource['clock_in_time'] ?? $this->clock_in_time,
            'clock_in_date' => $this->resource['clock_in_date'] ?? $this->clock_in_date,
        ];
    }
}
