<?php

namespace App\Services\Administration\Penalty;

use Exception;
use App\Models\Penalty\Penalty;
use App\Models\Attendance\Attendance;

class PenaltyService
{
    /**
     * Store a new penalty
     */
    public function store(array $data): Penalty
    {
        // Validate that the attendance belongs to the selected user
        $attendance = Attendance::where('id', $data['attendance_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if (!$attendance) {
            throw new Exception('Invalid attendance record for the selected employee.');
        }

        // Create the penalty
        $penalty = Penalty::create([
            'user_id' => $data['user_id'],
            'attendance_id' => $data['attendance_id'],
            'type' => $data['type'],
            'total_time' => $data['total_time'],
            'reason' => $data['reason'],
            'creator_id' => auth()->id(),
        ]);

        return $penalty;
    }

    /**
     * Get penalties with optional filtering
     */
    public function getPenaltiesQuery($request = null)
    {
        $query = Penalty::query();

        if ($request) {
            // Add filtering logic here if needed
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        return $query;
    }
}
