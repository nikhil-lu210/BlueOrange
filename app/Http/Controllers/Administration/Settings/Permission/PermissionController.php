<?php

namespace App\Http\Controllers\Administration\Settings\Permission;

use Exception;
use Illuminate\Http\Request;
use App\Models\PermissionModule;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Administration\Settings\Permission\PermissionStoreRequest;
use App\Http\Requests\Administration\Settings\Permission\PermissionUpdateRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = PermissionModule::with(['permissions'])->orderBy('name', 'asc')->get();
        // dd($modules[0]);
        return view('administration.settings.permission.index', compact(['modules']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = PermissionModule::orderBy('name', 'asc')->get();

        return view('administration.settings.permission.create', compact(['modules']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionStoreRequest $request)
    {
        // dd($request->all());
        
        $permissionModuleId = $request->input('permission_module_id');
        $moduleName = PermissionModule::whereId($permissionModuleId)->value('name');
        
        $actions = ['Create', 'Read', 'Update', 'Delete'];

        try {
            foreach ($actions as $action) {
                if ($request->has('name.' . $action)) {
                    $permissionName = ucfirst($moduleName) . ' ' . ucfirst($action);
                    
                    Permission::create([
                        'permission_module_id' => $permissionModuleId,
                        'name' => $permissionName,
                    ]);
                }
            }

            toast('New Permission Has Been Created.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        dd($request->all(), $permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
