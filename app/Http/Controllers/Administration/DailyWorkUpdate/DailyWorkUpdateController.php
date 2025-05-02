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

        // Filter team leaders using cached permissions
        $teamLeaders = User::whereIn('id', $userIds)
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
     *
     * @param array $userIds
     */
    protected function preloadPermissionsForUsers(array $userIds)
    {
        // Skip if already loaded
        if (!empty(self::$userPermissionsCache)) {
            return;
        }

        // Load all permissions for these users in a single query
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
    }

    /**
     * Check if a user has any of the given permissions using the cache
     *
     * @param User $user
     * @param array $permissions
     * @return bool
     */
    protected function userHasAnyPermission($user, array $permissions)
    {
        // If not in cache, fall back to the standard method
        if (!isset(self::$userPermissionsCache[$user->id])) {
            return $user->hasAnyPermission($permissions);
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
     * Display my work updates
     */
    public function my(Request $request)
    {
        // dd(auth()->user()->tl_employees);
        $roles = Role::select(['id', 'name'])->with(['users' => function ($query) {
                            $query->permission('Daily Work Update Create')
                                ->select(['id', 'name'])
                                ->whereIn('id', auth()->user()->tl_employees->pluck('id'))
                                ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                                ->whereStatus('Active');
                        }])->get();

        $authUserID = auth()->id();

        if (!$request->has('filter_work_updates') && auth()->user()->tl_employees_daily_work_updates->count() < 1) {
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
                Mail::to($teamLeader->employee->official_email)->send(new DailyWorkUpdateRequestMail($workUpdate, $teamLeader));
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
     */
    public function edit(DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyWorkUpdate $dailyWorkUpdate)
    {
        // dd($request->all(), $comment);
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

            toast('Daily Work Update Has Been Rated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
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

        // Get users with the required permission
        $users = User::select(['id', 'name'])
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

        // Attach users to their roles
        foreach ($roles as $role) {
            $roleUsers = $users->filter(function($user) use ($role) {
                return $user->roles->contains('id', $role->id);
            });
            $role->setRelation('users', $roleUsers);
        }

        return $roles;
    }

    /**
     * Helper method to filter Daily Work Updates
     */
    private function getFilteredDailyWorkUpdates(Request $request, $teamLeaderId = null)
    {
        $query = DailyWorkUpdate::query()->orderByDesc('created_at');

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
