<?php

namespace App\Http\Controllers\Administration\Attendance;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use App\Imports\Administration\Attendance\AttendanceImport;

class AttendanceImportController extends Controller
{
    public function index()
    {
        return view('administration.attendance.import');
    }


    public function upload(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimetypes:text/plain,text/csv',
        ]);

        try {
            // Process the file
            Excel::import(new AttendanceImport(), $request->file('import_file'));
        
            toast('Attendances imported successfully.', 'success');
            return redirect()->back();
        } catch (ValidationException $e) {
            // Handle validation errors from Excel import
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return back()->withError('An error occurred during import: ' . $e->getMessage())->withInput();
        }
    }
}
