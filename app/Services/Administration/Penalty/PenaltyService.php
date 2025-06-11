<?php

namespace App\Services\Administration\Penalty;

use Exception;
use App\Models\User;
use App\Models\Penalty\Penalty;
use App\Models\Attendance\Attendance;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Penalty\PenaltyCreatedMail;
use App\Notifications\Administration\Penalty\PenaltyCreatedNotification;

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

        // Load necessary relationships for notifications
        $penalty->load(['user.employee', 'attendance', 'creator.employee']);

        // Send notifications
        $this->sendPenaltyNotifications($penalty);

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

    /**
     * Send penalty notifications to employee and team leader
     */
    private function sendPenaltyNotifications(Penalty $penalty): void
    {
        $employee = $penalty->user;
        $creator = auth()->user();

        // 1. Notify the employee who received the penalty
        if ($employee && $employee->employee && $employee->employee->official_email) {
            // Send in-app notification
            $employee->notify(new PenaltyCreatedNotification($penalty, $creator));

            // Send email notification
            Mail::to($employee->employee->official_email)
                ->queue(new PenaltyCreatedMail($penalty, $employee));
        }

        // 2. Notify the employee's active team leader
        $activeTeamLeader = $this->getActiveTeamLeader($employee);

        if ($activeTeamLeader && $activeTeamLeader->employee && $activeTeamLeader->employee->official_email) {
            // Send in-app notification (with team member context)
            $activeTeamLeader->notify(new PenaltyCreatedNotification($penalty, $creator, true));

            // Send email notification
            Mail::to($activeTeamLeader->employee->official_email)
                ->queue(new PenaltyCreatedMail($penalty, $activeTeamLeader));
        }
    }

    /**
     * Get the active team leader for an employee
     */
    private function getActiveTeamLeader(User $employee): ?User
    {
        return $employee->employee_team_leaders()
            ->wherePivot('is_active', true)
            ->with(['employee'])
            ->first();
    }
}
