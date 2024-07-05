<?php

namespace App\Http\Controllers\Administration\Announcement;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Announcement\Announcement;
use App\Models\Announcement\AnnouncementComment;

class AnnouncementCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Announcement $announcement)
    {
        // dd($request->all(), $announcement->id);
        $request->validate([
            'comment' => 'required|string|min:10|max:255',
        ]);
        
        try {
            AnnouncementComment::create([
                'announcement_id' => $announcement->id,
                'commenter_id' => auth()->id(),
                'comment' => $request->comment
            ]);
            
            toast('Announcement Comment Submitted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while creating the announcement: ' . $e->getMessage());
        }
    }
}
