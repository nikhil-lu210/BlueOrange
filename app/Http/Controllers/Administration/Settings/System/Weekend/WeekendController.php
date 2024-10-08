<?php

namespace App\Http\Controllers\Administration\Settings\System\Weekend;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Settings\System\Holiday\HolidayStoreRequest;
use App\Http\Requests\Administration\Settings\System\Holiday\HolidayUpdateRequest;
use App\Models\Weekend\Weekend;

class WeekendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Holiday::select(['id', 'name', 'date', 'description', 'is_active']);

        if ($request->has('month_year') && !is_null($request->month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->month_year);

            $query->whereYear('date', $monthYear->year)->whereMonth('date', $monthYear->month);
        }

        $holidays = $query->get();

        return view('administration.settings.system.holiday.index', compact(['holidays']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     */
    public function show(Weekend $weekend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Weekend $weekend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HolidayUpdateRequest $request, Weekend $weekend)
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
}
