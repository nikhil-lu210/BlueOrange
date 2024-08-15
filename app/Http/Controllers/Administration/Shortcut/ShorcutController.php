<?php

namespace App\Http\Controllers\Administration\Shortcut;

use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Models\Shortcut\Shortcut;
use App\Http\Controllers\Controller;

class ShorcutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shortcuts = Shortcut::whereUserId(Auth::id())->get();

        return view('administration.shortcut.index', compact(['shortcuts']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.shortcut.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Shortcut::create([
                'icon' => $request->icon,
                'name' => $request->name,
                'url' => $request->url
            ]);

            toast('Shortcut assigned successfully.', 'success');
            return redirect()->route('administration.shortcut.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shortcut $shortcut)
    {
        abort_if(
            !($shortcut->user_id == auth()->user()->id),
            403,
            'You are not authorized to edit this shortcut.'
        );

        return view('administration.shortcut.edit', compact(['shortcut']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shortcut $shortcut)
    {
        abort_if(
            !($shortcut->user_id == auth()->user()->id),
            403,
            'You are not authorized to update this shortcut.'
        );

        try {
            $shortcut->update([
                'icon' => $request->icon,
                'name' => $request->name,
                'url' => $request->url
            ]);

            toast('Shortcut updated successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut)
    {
        abort_if(
            !($shortcut->user_id == auth()->user()->id),
            403,
            'You are not authorized to delete this shortcut.'
        );

        try {
            $shortcut->delete();

            toast('Shortcut deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
