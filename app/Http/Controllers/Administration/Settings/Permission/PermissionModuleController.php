<?php

namespace App\Http\Controllers\Administration\Settings\Permission;

use Exception;
use Illuminate\Http\Request;
use App\Models\PermissionModule;
use App\Http\Controllers\Controller;

class PermissionModuleController extends Controller
{
    public function store(Request $request) {
        // dd($request->all());
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:permission_modules,name'],
        ], [
            'name.unique' => 'The Permission Module Name Has Already Been Taken.'
        ]);

        try {
            PermissionModule::create([
                'name' => $request->name
            ]);

            toast('New Permission Module Has Been Created.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PermissionModule $module)
    {
        $modules = PermissionModule::orderBy('name', 'asc')->get();

        return view('administration.settings.permission.show', compact(['modules', 'module']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermissionModule $module)
    {
        //
    }
}
