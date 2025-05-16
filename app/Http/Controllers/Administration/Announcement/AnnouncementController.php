<?php

namespace App\Http\Controllers\Administration\Announcement;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Announcement\Announcement;
use App\Mail\Administration\Announcement\NewAnnouncementMail;
use App\Http\Requests\Administration\Announcement\AnnouncementStoreRequest;
use App\Http\Requests\Administration\Announcement\AnnouncementUpdateRequest;
use App\Notifications\Administration\Announcement\AnnouncementCreateNotification;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::select(['id', 'name'])
                        ->with([
                            'users' => function ($user) {
                                $user->permission('Announcement Create')
                                    ->select(['id', 'name'])
                                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                                    ->whereStatus('Active');
                            }
                        ])
                        ->whereHas('users', function ($user) {
                            $user->permission('Announcement Create');
                        })
                        ->distinct()
                        ->get();

        $query = Announcement::orderByDesc('created_at');

        if ($request->has('announcer_id') && !is_null($request->announcer_id)) {
            $query->where('announcer_id', $request->announcer_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::parse($request->created_month_year);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        }

        $announcements = $query->get();

        return view('administration.announcement.index', compact(['roles', 'announcements']));
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
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.announcement.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnnouncementStoreRequest $request)
    {
        // dd($request->all(), auth()->id());
        try {
            DB::transaction(function() use ($request) {
                $announcement = Announcement::create([
                    'announcer_id' => auth()->id(),
                    'recipients' => $request->recipients ?? null,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'announcements/' . $announcement->id;
                        store_file_media($file, $announcement, $directory);
                    }
                }

                $notifiableUsers = [];
                if (is_null($announcement->recipients)) {
                    $notifiableUsers = User::select(['id', 'name', 'email'])->where('id', '!=', $announcement->announcer_id)->get();
                } else {
                    $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $announcement->recipients)->get();
                }

                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new AnnouncementCreateNotification($announcement, auth()->user()));

                    // Mail::to($notifiableUser->email)->send(new NewAnnouncementMail($announcement, $notifiableUser));
                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    Mail::to($notifiableUser->employee->official_email)->queue(new NewAnnouncementMail($announcement, $notifiableUser));
                }
            });

            toast('Announcement assigned successfully.', 'success');
            return redirect()->route('administration.announcement.index');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the announcement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        // dd($announcement);
        if ($announcement->isAuthorized() == false) {
            abort(403, "You do not have permission to view this announcement ({$announcement->title}).");
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
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

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
