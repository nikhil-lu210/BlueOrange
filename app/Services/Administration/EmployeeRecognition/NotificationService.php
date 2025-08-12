<?php

declare(strict_types=1);

namespace App\Services\Administration\EmployeeRecognition;

use App\Mail\Administration\EmployeeRecognition\TeamLeaderRecognitionReminderMail;
use App\Models\User;
use App\Models\User\Employee\EmployeeRecognition;
use App\Notifications\Administration\Recognition\EmployeeRecognitionPublished;
use App\Notifications\Administration\Recognition\TeamLeaderRecognitionReminder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Notify employees of their recognition
     */
    public function notifyEmployeesOfRecognition(User $teamLeader, Carbon $month): void
    {
        $month = $month->copy()->startOfMonth();
        $rows = EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->get();

        foreach ($rows as $row) {
            $employee = $row->employee;
            if ($employee) {
                $employee->notify(new EmployeeRecognitionPublished($row));
            }
        }
    }
    
    /**
     * Send recognition reminders to team leaders
     */
    public function sendTeamLeaderReminders(?string $monthParam = null): void
    {
        $month = $monthParam ? Carbon::parse($monthParam)->startOfMonth() : now()->startOfMonth();
        $monthLabel = $month->format('F Y');
        
        // Get all team leaders with active team members
        $teamLeaders = User::whereHas('tl_employees', function ($q) {
            $q->wherePivot('is_active', true);
        })->get();
        
        foreach ($teamLeaders as $teamLeader) {
            // Find active team members without recognition for this month
            $missingEmployees = $this->getMissingEmployeesList($teamLeader, $month);
            
            if (!$missingEmployees->isEmpty()) {
                // Prepare data for notification
                $data = [
                    'month_label' => $monthLabel,
                    'month' => $month->format('Y-m-d'),
                    'missing' => $missingEmployees->map(function ($e) {
                        return [
                            'id' => $e->id,
                            'name' => $e->name,
                            'email' => $e->email,
                        ];
                    })->toArray(),
                ];
                
                // Send database notification
                $teamLeader->notify(new TeamLeaderRecognitionReminder($data['missing'], $monthLabel));
                
                // Send email
                Mail::to($teamLeader)->queue(new TeamLeaderRecognitionReminderMail($data, $teamLeader));
            }
        }
    }
    
    /**
     * Get list of team members without recognition for a month
     */
    private function getMissingEmployeesList(User $teamLeader, Carbon $month): \Illuminate\Database\Eloquent\Collection
    {
        $employeesWithRecognition = EmployeeRecognition::where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->pluck('employee_id')
            ->toArray();
        
        return $teamLeader->tl_employees()
            ->wherePivot('is_active', true)
            ->whereNotIn('users.id', $employeesWithRecognition)
            ->get();
    }
}