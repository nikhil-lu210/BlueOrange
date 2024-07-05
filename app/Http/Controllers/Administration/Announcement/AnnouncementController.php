<?php

namespace App\Http\Controllers\Administration\Announcement;

use Exception;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
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
        $announcements = Announcement::all();
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
        $user = auth()->user();

        $hasPermission = $user->hasPermissionTo('Announcement Create') || $user->hasPermissionTo('Announcement Update');
        $isRecipient = is_null($announcement->recipients) || in_array($user->id, $announcement->recipients);

        if ($hasPermission && $isRecipient) {
            abort(403, 'You do not have permission to view this announcement.');
        }
        
        dd($announcement);
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
        dd($announcement);
    }
}
