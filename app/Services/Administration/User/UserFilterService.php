<?php

namespace App\Services\Administration\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserFilterService
{
    /**
     * Get advanced filter data including all filter options and filtered users
     */
    public function getAdvancedFilterData(Request $request): array
    {
        $userService = new UserService();
        
        // Get all filter options
        $teamLeaders = $this->getTeamLeaders();
        $roles = $userService->getAllRoles();
        $religions = $userService->getAllReligions();
        $institutes = $userService->getAllInstitutes();
        $educationLevels = $userService->getAllEducationLevels();
        
        // Get blood group options (ENUM values)
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        // Get gender options
        $genders = ['Male', 'Female', 'Other'];
        
        // Get status options
        $statuses = ['Active', 'Inactive', 'Fired', 'Resigned'];

        // Apply filters and get users (initially empty if no filters applied)
        $users = $this->getFilteredUsers($request);

        return compact(
            'request', 'users', 'teamLeaders', 'roles', 'religions', 
            'institutes', 'educationLevels', 'bloodGroups', 'genders', 'statuses'
        );
    }

    /**
     * Get team leaders for filtering
     */
    public function getTeamLeaders(): Collection
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        return User::whereIn('id', $userIds)
                    ->whereStatus('Active')
                    ->get()
                    ->filter(function ($user) {
                        return $user->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete']);
                    });
    }

    /**
     * Get filtered users based on request parameters
     */
    public function getFilteredUsers(Request $request): Collection
    {
        // If no filters are applied, return empty collection
        if (!$this->hasAnyFilters($request)) {
            return collect();
        }

        $userIds = auth()->user()->user_interactions->pluck('id');

        $query = User::select(['id', 'userid', 'first_name', 'last_name', 'name', 'email', 'status', 'created_at', 'updated_at'])
                    ->with(['media', 'employee.religion', 'employee.institute', 'employee.education_level', 'roles:id,name', 'employee_shifts' => function($query) {
                        $query->where('status', 'Active')->latest('created_at');
                    }]);

        // Check if the authenticated user has permissions to see all users
        $authUser = auth()->user();
        if (!$authUser->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete'])) {
            $query->whereIn('id', $userIds);
        }

        // Apply all filters
        $this->applyAdvancedFilters($query, $request);

        // Default sorting
        $query->orderBy('name');

        return $query->get();
    }

    /**
     * Check if any filters are applied
     */
    public function hasAnyFilters(Request $request): bool
    {
        $filterFields = [
            'userid', 'name', 'email', 'status', 'team_leader_id', 'role_id',
            'alias_name', 'joining_date_from', 'joining_date_to', 'birth_date_from', 'birth_date_to',
            'gender', 'blood_group', 'religion_id', 'institute_id', 'education_level_id',
            'passing_year_from', 'passing_year_to', 'start_time', 'end_time',
            'created_from', 'created_to', 'updated_from', 'updated_to',
            'personal_email', 'official_email', 'personal_contact_no', 'official_contact_no'
        ];

        foreach ($filterFields as $field) {
            if ($request->filled($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply advanced filters to the query
     */
    private function applyAdvancedFilters($query, Request $request): void
    {
        // User basic information filters
        if ($request->filled('userid')) {
            $query->where('userid', 'LIKE', '%' . $request->userid . '%');
        }

        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('first_name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filters for user
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        if ($request->filled('updated_from')) {
            $query->whereDate('updated_at', '>=', $request->updated_from);
        }

        if ($request->filled('updated_to')) {
            $query->whereDate('updated_at', '<=', $request->updated_to);
        }

        // Role filter
        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($role) => $role->where('roles.id', $request->role_id));
        }

        // Team leader filter
        if ($request->filled('team_leader_id')) {
            $query->whereHas('employee_team_leaders', function ($tlQuery) use ($request) {
                $tlQuery->where('is_active', true)
                    ->where('team_leader_id', $request->team_leader_id);
            });
        }

        // Employee information filters
        $this->applyEmployeeFilters($query, $request);

        // Shift filters
        $this->applyShiftFilters($query, $request);
    }

    /**
     * Apply employee-related filters
     */
    private function applyEmployeeFilters($query, Request $request): void
    {
        $query->whereHas('employee', function($employeeQuery) use ($request) {
            // Alias name filter
            if ($request->filled('alias_name')) {
                $employeeQuery->where('alias_name', 'LIKE', '%' . $request->alias_name . '%');
            }

            // Joining date range filter
            if ($request->filled('joining_date_from')) {
                $employeeQuery->whereDate('joining_date', '>=', $request->joining_date_from);
            }

            if ($request->filled('joining_date_to')) {
                $employeeQuery->whereDate('joining_date', '<=', $request->joining_date_to);
            }

            // Birth date range filter
            if ($request->filled('birth_date_from')) {
                $employeeQuery->whereDate('birth_date', '>=', $request->birth_date_from);
            }

            if ($request->filled('birth_date_to')) {
                $employeeQuery->whereDate('birth_date', '<=', $request->birth_date_to);
            }

            // Gender filter
            if ($request->filled('gender')) {
                $employeeQuery->where('gender', $request->gender);
            }

            // Blood group filter
            if ($request->filled('blood_group')) {
                $employeeQuery->where('blood_group', $request->blood_group);
            }

            // Religion filter
            if ($request->filled('religion_id')) {
                $employeeQuery->where('religion_id', $request->religion_id);
            }

            // Institute filter
            if ($request->filled('institute_id')) {
                $employeeQuery->where('institute_id', $request->institute_id);
            }

            // Education level filter
            if ($request->filled('education_level_id')) {
                $employeeQuery->where('education_level_id', $request->education_level_id);
            }

            // Passing year range filter
            if ($request->filled('passing_year_from')) {
                $employeeQuery->where('passing_year', '>=', $request->passing_year_from);
            }

            if ($request->filled('passing_year_to')) {
                $employeeQuery->where('passing_year', '<=', $request->passing_year_to);
            }

            // Email filters
            if ($request->filled('personal_email')) {
                $employeeQuery->where('personal_email', 'LIKE', '%' . $request->personal_email . '%');
            }

            if ($request->filled('official_email')) {
                $employeeQuery->where('official_email', 'LIKE', '%' . $request->official_email . '%');
            }

            // Contact number filters
            if ($request->filled('personal_contact_no')) {
                $employeeQuery->where('personal_contact_no', 'LIKE', '%' . $request->personal_contact_no . '%');
            }

            if ($request->filled('official_contact_no')) {
                $employeeQuery->where('official_contact_no', 'LIKE', '%' . $request->official_contact_no . '%');
            }
        });
    }

    /**
     * Apply shift-related filters
     */
    private function applyShiftFilters($query, Request $request): void
    {
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $startTime = $request->input('start_time') . ':00';
            $endTime = $request->input('end_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($startTime, $endTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('start_time', '=', $startTime)
                    ->whereTime('end_time', '=', $endTime);
            });
        } elseif ($request->filled('start_time')) {
            $startTime = $request->input('start_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($startTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('start_time', '=', $startTime);
            });
        } elseif ($request->filled('end_time')) {
            $endTime = $request->input('end_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($endTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('end_time', '=', $endTime);
            });
        }
    }
}
