<?php

namespace App\Http\Controllers\Administration\Announcement;

use Exception;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement\Announcement;
use App\Http\Requests\Administration\Announcement\AnnouncementStoreRequest;
use App\Http\Requests\Administration\Announcement\AnnouncementUpdateRequest;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with(['announcer'])->orderBy('created_at', 'desc')->get();
        return view('administration.announcement.index', compact(['announcements']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $announcements = Announcement::all()->filter(function ($announcement) {
            return $announcement->isAuthorized();
        });
        // dd($announcements);
        return view('administration.announcement.my', compact(['announcements']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::with(['users'])->get();
        return view('administration.announcement.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnnouncementStoreRequest $request)
    {
        // dd($request->all(), auth()->id());
        try {
            Announcement::create([
                'announcer_id' => auth()->id(),
                'recipients' => $request->recipients ?? null,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            
            toast('Announcement assigned successfully.', 'success');
            return redirect()->route('administration.announcement.index');
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while creating the announcement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        // dd($announcement);
        if ($announcement->isAuthorized() == false) {
            abort(403, 'You do not have permission to view this announcement.');
        }

        // Fetch current read_by_at value and convert it to array
        $readBy = $announcement->read_by_at ? json_decode($announcement->read_by_at, true) : [];

        // Get current user ID
        $userId = Auth::id();

        // Check if user already read the announcement
        $userRead = collect($readBy)->firstWhere('read_by', $userId);

        if (!$userRead) {
            // User has not read the announcement yet, add new entry
            $readBy[] = [
                'read_by' => $userId,
                'read_at' => now()->toDateTimeString(),
            ];

            // Update announcement with updated read_by_at array
            $announcement->update(['read_by_at' => json_encode($readBy)]);
        }
        
        // dd($announcement->isAuthorized());
        return view('administration.announcement.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        // dd($announcement);
        $roles = Role::with(['users'])->get();
        return view('administration.announcement.edit', compact(['announcement', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnnouncementUpdateRequest $request, Announcement $announcement)
    {
        // dd($request->all(), $announcement);
        try {
            $announcement->update([
                'title' => $request->title,
                'description' => $request->description,
                'recipients' => $request->recipients ?? null
            ]);
            
            toast('Announcement updated successfully.', 'success');
            return redirect()->route('administration.announcement.show', ['announcement' => $announcement]);
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while updating the announcement: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            
            toast('Announcement deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
