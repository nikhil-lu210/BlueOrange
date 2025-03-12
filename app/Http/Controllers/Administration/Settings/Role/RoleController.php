<?php

namespace App\Http\Controllers\Administration\Settings\Role;

use Exception;
use Illuminate\Http\Request;
use App\Models\PermissionModule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Administration\Settings\Role\RoleStoreRequest;
use App\Http\Requests\Administration\Settings\Role\RoleUpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with([
            'users' => function($userQuery) {
                $userQuery->whereStatus('Active');
            },
            'permissions'
        ])
        ->withCount(['users as active_users_count' => function($query) {
            $query->whereStatus('Active');
        }])
        ->get();

        return view('administration.settings.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = PermissionModule::with(['permissions'])->get();
        // dd($modules);
        return view('administration.settings.role.create', compact(['modules']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        // dd($request->all());
        try {
            DB::transaction(function() use ($request) {
                $role = Role::create([
                    'name' => $request->name,
                ]);

                $permissionIds = $request->input('permissions', []);

                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
            }, 5);

            toast('Role Has Been Created.','success');
            return redirect()->back();
        } catch (Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissionModules = PermissionModule::whereHas('permissions.roles', function ($query) use ($role) {
            $query->where('name', $role->name);
        })->with(['permissions' => function ($query) use ($role) {
            $query->whereHas('roles', function ($roleQuery) use ($role) {
                $roleQuery->where('name', $role->name);
            });
        }])->get();

        return view('administration.settings.role.show', compact('role', 'permissionModules'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        abort_if(!auth()->user()->hasAnyRole(['Developer', 'Super Admin']) || ($role->name == 'Developer' && $role->name == 'Super Admin'), 403, 'You are not authorize to view this role\'s edit page.');

        $modules = PermissionModule::with(['permissions'])->get();
        // dd($modules);
        return view('administration.settings.role.edit', compact(['modules', 'role']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        abort_if(!auth()->user()->hasAnyRole(['Developer', 'Super Admin']) || ($role->name == 'Developer' && $role->name == 'Super Admin'), 403, 'You are not authorize to update this role.');

        try {
            DB::transaction(function() use ($request, $role) {
                $role->update([
                    'name' => $request->name ? $request->name : $role->name,
                    'updated_at' => now()
                ]);

                $permissionIds = $request->input('permissions', []);

                $permissions = Permission::whereIn('id', $permissionIds)->get();

                $role->syncPermissions($permissions);
            }, 5);

            toast('Role Has Been Updated.','success');
            return redirect()->back();
        } catch (Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        dd($role);
    }
}
