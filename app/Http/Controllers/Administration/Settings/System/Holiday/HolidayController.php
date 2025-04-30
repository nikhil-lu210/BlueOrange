<?php

namespace App\Http\Controllers\Administration\Settings\System\Holiday;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Administration\Settings\System\Holiday\HolidayStoreRequest;
use App\Http\Requests\Administration\Settings\System\Holiday\HolidayUpdateRequest;
use App\Imports\Administration\Settings\HolidayImport;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Holiday::select(['id', 'name', 'date', 'description', 'is_active']);

        if ($request->has('month_year') && !is_null($request->month_year)) {
            $monthYear = Carbon::parse($request->month_year);

            $query->whereYear('date', $monthYear->year)->whereMonth('date', $monthYear->month);
        }

        $holidays = $query->orderByDesc('date')->get();

        return view('administration.settings.system.holiday.index', compact(['holidays']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HolidayStoreRequest $request)
    {
        try {
            Holiday::create([
                'name' => $request->input('name'),
                'date' => $request->input('date'),
                'description' => $request->input('description')
            ]);
            toast('Holiday assigned successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(HolidayUpdateRequest $request, Holiday $holiday)
    {
        try {
            $holiday->name = $request->input('name');
            $holiday->date = $request->input('date');
            $holiday->description = $request->input('description');
            $holiday->is_active = $request->has('is_active');
    
            $holiday->save();
    
            toast('Holiday updated successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        try {
            $holiday->delete();
            
            toast('Holiday deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * import a newly created resource in storage.
     */
    public function import(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'import_file' => 'required|file|mimetypes:text/plain,text/csv',
        ]);

        try {
            // Process the file
            Excel::import(new HolidayImport(), $request->file('import_file'));

            toast('Holidays imported successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError('An error occurred during import: ' . $e->getMessage())->withInput();
        }
    }
    
}
