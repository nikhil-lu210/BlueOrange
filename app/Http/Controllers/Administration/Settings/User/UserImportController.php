<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Administration\User\UserImport;

class UserImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return view('administration.settings.user.import', compact(['roles']));
    }


    /**
     * Import and Upload users
     */
    public function upload(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'import_file' => 'required|file|mimetypes:text/plain,text/csv',
        ]);

        try {
            // Process the file
            Excel::import(new UserImport($request->role_id), $request->file('import_file'));

            toast('Users imported successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError('An error occurred during import: ' . $e->getMessage())->withInput();
        }
    }
}
