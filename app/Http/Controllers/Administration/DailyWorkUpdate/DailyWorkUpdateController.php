<?php

namespace App\Http\Controllers\Administration\DailyWorkUpdate;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Http\Requests\Administration\DailyWorkUpdate\DailyWorkUpdateStoreRequest;
use App\Mail\Administration\DailyWorkUpdate\DailyWorkUpdateRequestMail;
use App\Notifications\Administration\DailyWorkUpdate\DailyWorkUpdateCreateNotification;
use App\Notifications\Administration\DailyWorkUpdate\DailyWorkUpdateUpdateNotification;

class DailyWorkUpdateController extends Controller
{
    // Cache for user permissions to avoid duplicate queries
    protected static $userPermissionsCache = [];

    // Cache for user roles to avoid duplicate queries
    protected static $userRolesCache = [];

    // Flag to track if we've already loaded permissions for the current request
    protected static $permissionsLoaded = false;

    // Flag to track if we've already loaded roles for the current request
    protected static $rolesLoaded = false;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $authUser = auth()->user();

        // Get user interactions using the optimized accessor (now cached)
        $userIds = $authUser->user_interactions->pluck('id');

        // Preload permissions for all users to avoid n+1 queries
        $this->preloadPermissionsForUsers($userIds->toArray());

        // Filter team leaders using cached permissions and load necessary relationships
        $teamLeaders = User::whereIn('id', $userIds)
                            ->with(['employee', 'roles']) // Load relationships needed for the view
                            ->whereStatus('Active')
                            ->get()
                            ->filter(function ($user) {
                                return $this->userHasAnyPermission($user, ['Daily Work Update Everything', 'Daily Work Update Update']);
                            });

        $roles = $this->getRolesWithPermission();

        $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request);

        return view('administration.daily_work_update.index', compact('teamLeaders', 'roles', 'dailyWorkUpdates'));
    }

    /**
     * Preload permissions for a set of users to avoid n+1 queries
     * This method is optimized to avoid duplicate queries by using a static flag
     *
     * @param array $userIds
     */
    protected function preloadPermissionsForUsers(array $userIds)
    {
        // Skip if already loaded in this request
        if (self::$permissionsLoaded) {
            return;
        }

        // Mark as loaded to prevent duplicate queries
        self::$permissionsLoaded = true;

        // Get the authenticated user's permissions first
        // This is to avoid the duplicate query from HasPermissions.php
        $authUser = auth()->user();
        if (!isset(self::$userPermissionsCache[$authUser->id])) {
            // Get permissions directly from the user model's permissions relation
            // This will use the already loaded permissions from the auth() call
            $authPermissions = $authUser->permissions->pluck('name')->toArray();

            // Also include permissions from roles
            foreach ($authUser->roles as $role) {
                $rolePermissions = $role->permissions->pluck('name')->toArray();
                $authPermissions = array_merge($authPermissions, $rolePermissions);
            }

            // Remove duplicates and store in cache
            self::$userPermissionsCache[$authUser->id] = array_unique($authPermissions);

            // Remove auth user from the list to avoid duplicate query
            $userIds = array_diff($userIds, [$authUser->id]);
        }

        // If there are no other users to load, return early
        if (empty($userIds)) {
            return;
        }

        // Load all permissions for other users in a single query
        $permissions = DB::table('permissions')
            ->select('permissions.name', 'model_has_permissions.model_id')
            ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->whereIn('model_has_permissions.model_id', $userIds)
            ->where('model_has_permissions.model_type', 'App\\Models\\User')
            ->get();

        // Organize permissions by user
        foreach ($permissions as $permission) {
            if (!isset(self::$userPermissionsCache[$permission->model_id])) {
                self::$userPermissionsCache[$permission->model_id] = [];
            }
            self::$userPermissionsCache[$permission->model_id][] = $permission->name;
        }

        // Also preload roles for these users to avoid duplicate queries
        $this->preloadRolesForUsers($userIds);
    }

    /**
     * Preload roles for a set of users to avoid n+1 queries
     * This method is optimized to avoid duplicate queries by using a static flag
     *
     * @param array $userIds
     */
    protected function preloadRolesForUsers(array $userIds)
    {
        // Skip if already loaded in this request
        if (self::$rolesLoaded) {
            return;
        }

        // Mark as loaded to prevent duplicate queries
        self::$rolesLoaded = true;

        // Get the authenticated user's roles first
        // This is to avoid the duplicate query
        $authUser = auth()->user();
        if (!isset(self::$userRolesCache[$authUser->id])) {
            // Get roles directly from the user model's roles relation
            // This will use the already loaded roles from the auth() call
            $authRoles = $authUser->roles->pluck('id')->toArray();
            self::$userRolesCache[$authUser->id] = $authRoles;

            // Remove auth user from the list to avoid duplicate query
            $userIds = array_diff($userIds, [$authUser->id]);
        }

        // If there are no other users to load, return early
        if (empty($userIds)) {
            return;
        }

        // Load all roles for other users in a single query
        $roles = DB::table('roles')
            ->select('roles.id', 'model_has_roles.model_id')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('model_has_roles.model_id', $userIds)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->get();

        // Organize roles by user
        foreach ($roles as $role) {
            if (!isset(self::$userRolesCache[$role->model_id])) {
                self::$userRolesCache[$role->model_id] = [];
            }
            self::$userRolesCache[$role->model_id][] = $role->id;
        }
    }

    /**
     * Check if a user has any of the given permissions using the cache
     * This method is optimized to avoid duplicate queries
     *
     * @param User $user
     * @param array $permissions
     * @return bool
     */
    protected function userHasAnyPermission($user, array $permissions)
    {
        // Make sure permissions are loaded for this user
        if (!isset(self::$userPermissionsCache[$user->id])) {
            // Load permissions for this user if not already in cache
            $this->preloadPermissionsForUsers([$user->id]);

            // If still not in cache after preloading, fall back to standard method
            if (!isset(self::$userPermissionsCache[$user->id])) {
                return $user->hasAnyPermission($permissions);
            }
        }

        // Check if any of the required permissions exist in the user's cached permissions
        foreach ($permissions as $permission) {
            if (in_array($permission, self::$userPermissionsCache[$user->id])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a user has a specific role using the cache
     * This method is optimized to avoid duplicate queries
     *
     * @param User $user
     * @param int|string $roleId
     * @return bool
     */
    protected function userHasRole($user, $roleId)
    {
        // Make sure roles are loaded for this user
        if (!isset(self::$userRolesCache[$user->id])) {
            // Load roles for this user if not already in cache
            $this->preloadRolesForUsers([$user->id]);

            // If still not in cache after preloading, fall back to standard method
            if (!isset(self::$userRolesCache[$user->id])) {
                return $user->roles->contains('id', $roleId);
            }
        }

        // Check if the role exists in the user's cached roles
        return in_array($roleId, self::$userRolesCache[$user->id]);
    }

    /**
     * Display my work updates
     */
    public function my(Request $request)
    {
        // Get the authenticated user
        $authUser = auth()->user();

        // Get user interactions and team employees
        $userIds = $authUser->user_interactions->pluck('id');
        $teamEmployeeIds = $authUser->tl_employees->pluck('id');

        // Combine and get unique user IDs
        $allUserIds = $userIds->merge($teamEmployeeIds)->unique()->toArray();

        // Preload permissions for all users to avoid n+1 queries
        $this->preloadPermissionsForUsers($allUserIds);

        // Get users with the required permission
        $usersWithPermission = [];
        foreach ($allUserIds as $userId) {
            if (isset(self::$userPermissionsCache[$userId]) &&
                in_array('Daily Work Update Create', self::$userPermissionsCache[$userId])) {
                $usersWithPermission[] = $userId;
            }
        }

        // Get users with the required permission and load necessary relationships
        $users = User::select(['id', 'name'])
            ->with(['employee', 'roles']) // Load relationships needed for the view
            ->whereIn('id', $usersWithPermission)
            ->whereStatus('Active')
            ->get();

        // Get roles for these users using the same approach as getRolesWithPermission
        $userRoleIds = DB::table('model_has_roles')
            ->whereIn('model_id', $users->pluck('id'))
            ->where('model_type', 'App\\Models\\User')
            ->pluck('role_id')
            ->unique();

        // Get roles with users
        $roles = Role::select(['id', 'name'])
            ->whereIn('id', $userRoleIds)
            ->get();

        // Attach users to their roles using our cached role check
        foreach ($roles as $role) {
            $roleUsers = $users->filter(function($user) use ($role) {
                return $this->userHasRole($user, $role->id);
            });
            $role->setRelation('users', $roleUsers);
        }

        $authUserID = $authUser->id;

        if (!$request->has('filter_work_updates') && $authUser->tl_employees_daily_work_updates->count() < 1) {
            $dailyWorkUpdates = DailyWorkUpdate::whereUserId($authUserID)
                                ->orWhere('team_leader_id', $authUserID)
                                ->orderByDesc('created_at')
                                ->get();
        } else {
            $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request, $authUserID);
        }

        return view('administration.daily_work_update.my', compact('roles', 'dailyWorkUpdates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.daily_work_update.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DailyWorkUpdateStoreRequest $request)
    {
        // dd($request->all(), auth()->user()->active_team_leader->id);
        $authUser = auth()->user();
        $teamLeader = $authUser->active_team_leader;

        if (is_null($teamLeader)) {
            return redirect()->back()->withInput()->withErrors('Team Leader is Missing. Please ask authority to assign your Team Leader');
        }

        try {
            DB::transaction(function () use ($request, $authUser, $teamLeader) {
                $workUpdate = DailyWorkUpdate::create([
                    'user_id' => $authUser->id,
                    'team_leader_id' => $teamLeader->id,
                    'date' => $request->date ?? date('Y-m-d'),
                    'work_update' => $request->work_update,
                    'progress' => $request->progress,
                    'note' => $request->note_issue ?? null
                ]);

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'daily_work_update/' . $authUser->userid;
                        store_file_media($file, $workUpdate, $directory);
                    }
                }

                // Send Notification to System
                $teamLeader->notify(new DailyWorkUpdateCreateNotification($workUpdate, auth()->user()));

                // Send Mail to the Team Leader
                // Mail::to($teamLeader->employee->official_email)->queue(new DailyWorkUpdateRequestMail($workUpdate, $teamLeader));
            });

            toast('Daily Work Update Has Been Submitted Successfully.', 'success');
            return redirect()->route('administration.daily_work_update.my');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyWorkUpdate $dailyWorkUpdate)
    {
        // dd($dailyWorkUpdate);
        return view('administration.daily_work_update.show', compact(['dailyWorkUpdate']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DailyWorkUpdate $dailyWorkUpdate
     * @return void
     *
     * @codeCoverageIgnore This method is not implemented yet
     */
    public function edit(DailyWorkUpdate $dailyWorkUpdate)
    {
        // This method is not implemented yet
        // The parameter is required by Laravel's resource controller pattern
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyWorkUpdate $dailyWorkUpdate)
    {
        // Validate rating if provided
        if ($request->has('rating')) {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $dailyWorkUpdate) {
                $comment = $request->input('comment');
                // Check if the comment contains only empty tags or line breaks
                if (trim(strip_tags($comment)) === '') {
                    $comment = null; // Or handle it as an empty comment
                }

                $dailyWorkUpdate->update([
                    'rating' => $request->rating,
                    'comment' => $comment
                ]);

                // Send Notification to System
                $dailyWorkUpdate->user->notify(new DailyWorkUpdateUpdateNotification($dailyWorkUpdate, auth()->user()));
            });

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Daily Work Update Has Been Rated Successfully.',
                    'rating' => $dailyWorkUpdate->fresh()->rating
                ]);
            }

            toast('Daily Work Update Has Been Rated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            // Log the error for debugging
            \Log::error('Daily Work Update rating error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'daily_work_update_id' => $dailyWorkUpdate->id ?? 'N/A'
            ]);

            // Handle AJAX error requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyWorkUpdate $dailyWorkUpdate)
    {
        try {
            $dailyWorkUpdate->forceDelete();

            toast('Daily Work Updated Has Been Deleted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Helper method to get roles with 'Daily Work Update Create' permission
     * Uses cached permissions to avoid duplicate queries
     */
    private function getRolesWithPermission()
    {
        // Get user interactions using the optimized accessor (now cached)
        $userIds = auth()->user()->user_interactions->pluck('id');

        // Preload permissions if not already loaded
        $this->preloadPermissionsForUsers($userIds->toArray());

        // Get all users with the required permission
        $usersWithPermission = [];
        foreach ($userIds as $userId) {
            if (isset(self::$userPermissionsCache[$userId]) &&
                in_array('Daily Work Update Create', self::$userPermissionsCache[$userId])) {
                $usersWithPermission[] = $userId;
            }
        }

        // Get users with the required permission and load necessary relationships
        $users = User::select(['id', 'name'])
            ->with(['employee', 'roles']) // Load relationships needed for the view
            ->whereIn('id', $usersWithPermission)
            ->whereStatus('Active')
            ->get();

        // Get roles for these users
        $userRoleIds = DB::table('model_has_roles')
            ->whereIn('model_id', $users->pluck('id'))
            ->where('model_type', 'App\\Models\\User')
            ->pluck('role_id')
            ->unique();

        // Get roles with users
        $roles = Role::select(['id', 'name'])
            ->whereIn('id', $userRoleIds)
            ->get();

        // Attach users to their roles using our cached role check
        foreach ($roles as $role) {
            $roleUsers = $users->filter(function($user) use ($role) {
                return $this->userHasRole($user, $role->id);
            });
            $role->setRelation('users', $roleUsers);
        }

        return $roles;
    }

    /**
     * Helper method to filter Daily Work Updates
     * Loads necessary relationships for the view
     */
    private function getFilteredDailyWorkUpdates(Request $request, $teamLeaderId = null)
    {
        $query = DailyWorkUpdate::query()
                ->with([
                    'user' => function($query) {
                        $query->with(['employee', 'roles', 'media']);
                    },
                    'team_leader' => function($query) {
                        $query->with(['employee', 'roles', 'media']);
                    }
                ])
                ->orderByDesc('created_at');

        if ($teamLeaderId) {
            $query->where('team_leader_id', $teamLeaderId);
        }

        if ($request->filled('team_leader_id')) {
            $query->where('team_leader_id', $request->team_leader_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('created_month_year')) {
            $monthYear = Carbon::parse($request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_work_updates')) {
            // Default to current month
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }

        if ($request->filled('status')) {
            $request->status === 'Reviewed' ? $query->whereNotNull('rating') : $query->whereNull('rating');
        }

        return $query->get();
    }
}
