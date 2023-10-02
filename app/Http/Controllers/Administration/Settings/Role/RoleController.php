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

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with(['permissions'])->get();
        // dd($roles);
        return view('administration.settings.role.index', compact(['roles']));
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
        dd($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        dd($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        dd($request->all(), $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        dd($role);
    }
}
