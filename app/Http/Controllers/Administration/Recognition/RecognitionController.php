<?php

namespace App\Http\Controllers\Administration\Recognition;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Recognition\Recognition;
use App\Notifications\Administration\Recognition\RecognitionCreatedNotification;

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
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Recognition submitted successfully',
        //     'data' => $recognition
        // ]);

        toast('Recognition Submitted', 'success');
        return redirect()->back();
    }

    public function markRecognizeAsRead()
    {
        Auth::user()
            ->notifications()
            ->where('type', RecognitionCreatedNotification::class)
            ->whereNull('read_at') // mark only unread
            ->update(['read_at' => now()]);

        toast('Recognition Notification(s) has been marked as read.', 'success');
        return redirect()->back();
    }
}
