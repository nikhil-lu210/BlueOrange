<?php

namespace App\Http\Controllers\Administration\Recognition;

use App\Http\Controllers\Controller;
use App\Models\Recognition\Recognition;
use Illuminate\Http\Request;

class RecognitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string|in:' . implode(',', config('recognition.categories')),
            'total_mark' => 'required|integer|min:' . config('recognition.marks.min') . '|max:' . config('recognition.marks.max'),
            'comment' => 'required|string|max:1000',
        ]);

        $recognition = Recognition::create([
            'user_id' => $validated['user_id'],
            'category' => $validated['category'],
            'total_mark' => $validated['total_mark'],
            'comment' => $validated['comment'],
            'recognizer_id' => auth()->id(),
        ]);

        // return success JSON
        return response()->json([
            'success' => true,
            'message' => 'Recognition submitted successfully',
            'data' => $recognition
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recognition $recognition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recognition $recognition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recognition $recognition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recognition $recognition)
    {
        //
    }
}
