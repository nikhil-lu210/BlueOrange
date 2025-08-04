<?php

namespace App\Services\Administration\Hiring;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Hiring\HiringCandidate;
use App\Models\User\Employee\Employee;
use App\Models\Hiring\HiringStageEvaluation;

class HiringService
{
    /**
     * Store a new hiring candidate
     */
    public function storeCandidate(array $data): HiringCandidate
    {
        return HiringCandidate::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'expected_role' => $data['expected_role'],
            'expected_salary' => $data['expected_salary'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'shortlisted',
            'current_stage' => 1,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Create stage evaluations based on assignments
     */
    public function createStageEvaluations(HiringCandidate $candidate, array $data): void
    {
        $stages = [
            1 => ['evaluator' => 'stage1_evaluator', 'scheduled_at' => 'stage1_scheduled_at'],
            2 => ['evaluator' => 'stage2_evaluator', 'scheduled_at' => 'stage2_scheduled_at'],
            3 => ['evaluator' => 'stage3_evaluator', 'scheduled_at' => 'stage3_scheduled_at'],
        ];

        foreach ($stages as $stageOrder => $stageData) {
            $stage = \App\Models\Hiring\HiringStage::where('stage_order', $stageOrder)->first();
            if (!$stage) continue;

            $evaluatorKey = $stageData['evaluator'];
            $scheduledAtKey = $stageData['scheduled_at'];

            // Single evaluator for all stages
            if (isset($data[$evaluatorKey]) && !empty($data[$evaluatorKey])) {
                HiringStageEvaluation::create([
                    'hiring_candidate_id' => $candidate->id,
                    'hiring_stage_id' => $stage->id,
                    'assigned_to' => $data[$evaluatorKey],
                    'scheduled_at' => $data[$scheduledAtKey] ?? null,
                    'status' => 'pending',
                    'assigned_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            }
        }
    }

    /**
     * Update a hiring candidate
     */
    public function updateCandidate(HiringCandidate $candidate, array $data): HiringCandidate
    {
        $candidate->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'expected_role' => $data['expected_role'],
            'expected_salary' => $data['expected_salary'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
        ]);

        return $candidate->fresh();
    }

    /**
     * Create or update a stage evaluation
     */
    public function storeOrUpdateEvaluation(array $data): HiringStageEvaluation
    {
        $evaluation = HiringStageEvaluation::updateOrCreate(
            [
                'hiring_candidate_id' => $data['hiring_candidate_id'],
                'hiring_stage_id' => $data['hiring_stage_id'],
            ],
            [
                'assigned_to' => $data['assigned_to'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'feedback' => $data['feedback'] ?? null,
                'rating' => $data['rating'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        );

        // Update timestamps based on status
        $this->updateEvaluationTimestamps($evaluation, $data['status']);

        return $evaluation;
    }

    /**
     * Progress candidate to next stage
     */
    public function progressToNextStage(HiringCandidate $candidate): bool
    {
        if ($candidate->current_stage >= 3) {
            return false; // Already at final stage
        }

        $candidate->update([
            'current_stage' => $candidate->current_stage + 1,
            'status' => 'in_progress'
        ]);

        return true;
    }

    /**
     * Reject a candidate
     */
    public function rejectCandidate(HiringCandidate $candidate, ?string $reason = null): HiringCandidate
    {
        $candidate->update([
            'status' => 'rejected',
            'notes' => $candidate->notes . ($reason ? "\n\nRejection Reason: " . $reason : '')
        ]);

        return $candidate;
    }

    /**
     * Complete hiring process and create user account
     */
    public function completeHiring(HiringCandidate $candidate, array $userData): User
    {
        return DB::transaction(function () use ($candidate, $userData) {
            // Create user account
            $user = User::create([
                'userid' => $userData['userid'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'name' => $userData['first_name'] . ' ' . $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'status' => 'Active',
            ]);

            // Create employee profile
            Employee::create([
                'user_id' => $user->id,
                'joining_date' => $userData['joining_date'],
                'alias_name' => $userData['alias_name'] ?? null,
                'official_email' => $userData['official_email'] ?? $userData['email'],
                'official_contact_no' => $userData['official_contact_no'] ?? $candidate->phone,
            ]);

            // Assign role
            if (isset($userData['role_id'])) {
                $role = \Spatie\Permission\Models\Role::find($userData['role_id']);
                if ($role) {
                    $user->assignRole($role);
                }
            }

            // Update candidate record
            $candidate->update([
                'status' => 'hired',
                'user_id' => $user->id,
                'hired_at' => now(),
            ]);

            return $user;
        });
    }

    /**
     * Get candidates with filters
     */
    public function getCandidatesQuery($request)
    {
        $query = HiringCandidate::with([
            'creator.employee',
            'user.employee',
            'evaluations.stage',
            'evaluations.assignedUser.employee'
        ]);

        // Apply filters - handle both Request objects and stdClass objects
        $status = is_object($request) && isset($request->status) ? $request->status : (isset($request['status']) ? $request['status'] : null);
        $stage = is_object($request) && isset($request->stage) ? $request->stage : (isset($request['stage']) ? $request['stage'] : null);
        $search = is_object($request) && isset($request->search) ? $request->search : (isset($request['search']) ? $request['search'] : null);
        $dateFrom = is_object($request) && isset($request->date_from) ? $request->date_from : (isset($request['date_from']) ? $request['date_from'] : null);
        $dateTo = is_object($request) && isset($request->date_to) ? $request->date_to : (isset($request['date_to']) ? $request['date_to'] : null);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($stage)) {
            $query->where('current_stage', $stage);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('expected_role', 'like', "%{$search}%");
            });
        }

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->orderByDesc('created_at');
    }

    /**
     * Get evaluations for a user
     */
    public function getMyEvaluationsQuery($userId)
    {
        return HiringStageEvaluation::with([
            'candidate',
            'stage',
            'creator.employee',
            'updater.employee'
        ])->where('assigned_to', $userId)
          ->orderByDesc('created_at');
    }

    /**
     * Update evaluation timestamps based on status
     */
    public function updateEvaluationTimestamps(HiringStageEvaluation $evaluation, string $status): void
    {
        $now = now();

        switch ($status) {
            case 'in_progress':
                if (!$evaluation->started_at) {
                    $evaluation->update(['started_at' => $now]);
                }
                break;
            case 'completed':
            case 'passed':
            case 'failed':
                if (!$evaluation->started_at) {
                    $evaluation->update(['started_at' => $now]);
                }
                if (!$evaluation->completed_at) {
                    $evaluation->update(['completed_at' => $now]);
                }
                break;
        }
    }

    /**
     * Check if evaluation is passed and progress candidate or reject
     */
    public function checkAndProgressCandidate(HiringCandidate $candidate, string $evaluationStatus): bool
    {
        if ($evaluationStatus === 'failed') {
            // If failed, mark candidate as rejected
            $candidate->update(['status' => 'rejected']);
            return true;
        }

        if ($evaluationStatus === 'passed') {
            // If passed, progress to next stage or mark as ready for hiring
            if ($candidate->current_stage < 3) {
                // Progress to next stage
                $candidate->update([
                    'current_stage' => $candidate->current_stage + 1,
                    'status' => 'in_progress'
                ]);
                return true;
            } else {
                // At final stage, mark as hired
                $candidate->update(['status' => 'hired']);
                return true;
            }
        }

        return false;
    }

    /**
     * Get available evaluators (users with appropriate permissions)
     */
    public function getAvailableEvaluators()
    {
        return User::with(['employee', 'roles'])
            ->whereStatus('Active')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Developer'])
                      ->orWhereHas('permissions', function ($permQuery) {
                          $permQuery->where('name', 'like', 'Employee Hiring%');
                      });
            })
            ->select(['id', 'name'])
            ->get();
    }
}
