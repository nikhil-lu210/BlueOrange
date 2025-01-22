<?php

namespace App\Http\Controllers\Administration\Settings\System\Weekend;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use App\Http\Controllers\Controller;
use App\Models\Weekend\Weekend;

class WeekendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $weekends = Weekend::select(['id', 'day', 'is_active'])->get();

        return view('administration.settings.system.weekend.index', compact(['weekends']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Weekend $weekend)
    {
        $request->validate([
            'is_active' => ['nullable','boolean'],
        ]);
        
        $weekend->update([
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->is_active == true) {
            $message = $weekend->day. ' has been assigned as weekend.';
        } else {
            $message = $weekend->day. ' has been removed as weekend.';
        }        

        toast($message, 'success');
        return redirect()->back();
    }

}
