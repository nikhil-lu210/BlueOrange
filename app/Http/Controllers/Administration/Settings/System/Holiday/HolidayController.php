<?php

namespace App\Http\Controllers\Administration\Settings\System\Holiday;

use Exception;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Settings\System\Holiday\HolidayStoreRequest;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::select(['id', 'name', 'date', 'description'])->orderBy('date', 'desc')->get();
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
            DB::transaction(function () use ($request) {
                Holiday::create([
                    'name' => $request->input('name'),
                    'date' => $request->input('date'),
                    'description' => $request->input('description')
                ]);
            });
            return redirect()->back()->with('success', 'Holiday added successfully.');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
