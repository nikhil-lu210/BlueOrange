<?php

namespace App\Http\Controllers\Administration\FunctionalityWalkthrough;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthroughStep;
use App\Mail\Administration\FunctionalityWalkthrough\NewWalkthroughMail;
use App\Http\Requests\Administration\FunctionalityWalkthrough\WalkthroughStoreRequest;
use App\Http\Requests\Administration\FunctionalityWalkthrough\WalkthroughUpdateRequest;
use App\Notifications\Administration\FunctionalityWalkthrough\WalkthroughCreateNotification;

class FunctionalityWalkthroughController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        $query = FunctionalityWalkthrough::with([
            'creator.employee',
            'creator.media',
            'creator.roles'
        ])->orderByDesc('created_at');

        if ($request->has('creator_id') && !is_null($request->creator_id)) {
            $query->where('creator_id', $request->creator_id);
        }

        if ($request->has('created_month_year') && !is_null($request->created_month_year)) {
            $monthYear = Carbon::parse($request->created_month_year);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        }

        $walkthroughs = $query->get();

        return view('administration.functionality_walkthrough.index', compact(['roles', 'walkthroughs']));
    }

    /**
     * Display user's walkthroughs.
     */
    public function my()
    {
        $walkthroughs = FunctionalityWalkthrough::with([
            'creator.employee',
            'creator.media',
            'creator.roles'
        ])->get()->filter(function ($walkthrough) {
            return $walkthrough->isAuthorized();
        });

        return view('administration.functionality_walkthrough.my', compact(['walkthroughs']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = FunctionalityWalkthrough::getRolesForRecipients();

        return view('administration.functionality_walkthrough.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalkthroughStoreRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $walkthrough = FunctionalityWalkthrough::create([
                    'creator_id' => auth()->id(),
                    'assigned_roles' => $request->assigned_roles ?? null,
                    'title' => $request->title,
                ]);

                // Store walkthrough steps
                if ($request->has('steps')) {
                    foreach ($request->steps as $index => $step) {
                        if (!empty($step['step_title']) && !empty($step['step_description'])) {
                            $walkthroughStep = FunctionalityWalkthroughStep::create([
                                'walkthrough_id' => $walkthrough->id,
                                'step_title' => $step['step_title'],
                                'step_description' => $step['step_description'],
                                'step_order' => $index + 1,
                            ]);

                            // Store step files if any
                            if (isset($step['files']) && is_array($step['files'])) {
                                foreach ($step['files'] as $file) {
                                    $directory = 'walkthrough_steps/' . $walkthroughStep->id;
                                    store_file_media($file, $walkthroughStep, $directory);
                                }
                            }
                        }
                    }
                }

                // Store walkthrough files if any
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'walkthroughs/' . $walkthrough->id;
                        store_file_media($file, $walkthrough, $directory);
                    }
                }

                $notifiableUsers = [];
                if (is_null($walkthrough->assigned_roles)) {
                    $notifiableUsers = User::with(['employee'])->select(['id', 'name', 'email'])->where('id', '!=', $walkthrough->creator_id)->get();
                } else {
                    // Get users with assigned roles
                    $notifiableUsers = User::with(['employee'])
                        ->whereHas('roles', function($query) use ($walkthrough) {
                            $query->whereIn('roles.id', $walkthrough->assigned_roles);
                        })
                        ->where('id', '!=', $walkthrough->creator_id)
                        ->get();
                }

                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new WalkthroughCreateNotification($walkthrough, auth()->user()));

                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    if ($notifiableUser->employee && $notifiableUser->employee->official_email) {
                        Mail::to($notifiableUser->employee->official_email)->queue(new NewWalkthroughMail($walkthrough, $notifiableUser));
                    }
                }
            });

            toast('Functionality Walkthrough created successfully.', 'success');
            return redirect()->route('administration.functionality_walkthrough.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the walkthrough: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        if ($functionalityWalkthrough->isAuthorized() == false) {
            abort(403, "You do not have permission to view this walkthrough ({$functionalityWalkthrough->title}).");
        }

        // Fetch current read_by_at value and convert it to array
        $readBy = $functionalityWalkthrough->read_by_at ? json_decode($functionalityWalkthrough->read_by_at, true) : [];

        // Get current user ID
        $userId = Auth::id();

        // Check if user already read the walkthrough
        $userRead = collect($readBy)->firstWhere('read_by', $userId);

        if (!$userRead) {
            // User has not read the walkthrough yet, add new entry
            $readBy[] = [
                'read_by' => $userId,
                'read_at' => now()->toDateTimeString(),
            ];

            // Update walkthrough with updated read_by_at array
            $functionalityWalkthrough->update(['read_by_at' => json_encode($readBy)]);
        }

        $walkthrough = FunctionalityWalkthrough::with([
                'creator.employee',
                'creator.media',
                'files',
                'steps.files'
            ])
            ->whereId($functionalityWalkthrough->id)
            ->firstOrFail();

        return view('administration.functionality_walkthrough.show', compact('walkthrough'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        $roles = FunctionalityWalkthrough::getRolesForRecipients();

        $walkthrough = FunctionalityWalkthrough::with(['steps.files', 'files'])
            ->whereId($functionalityWalkthrough->id)
            ->firstOrFail();

        return view('administration.functionality_walkthrough.edit', compact(['walkthrough', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WalkthroughUpdateRequest $request, FunctionalityWalkthrough $functionalityWalkthrough)
    {
        try {
            DB::transaction(function() use ($request, $functionalityWalkthrough) {
                $functionalityWalkthrough->update([
                    'title' => $request->title,
                    'assigned_roles' => $request->assigned_roles ?? null
                ]);

                // Delete existing steps
                $functionalityWalkthrough->steps()->delete();

                // Store updated walkthrough steps
                if ($request->has('steps')) {
                    foreach ($request->steps as $index => $step) {
                        if (!empty($step['step_title']) && !empty($step['step_description'])) {
                            $walkthroughStep = FunctionalityWalkthroughStep::create([
                                'walkthrough_id' => $functionalityWalkthrough->id,
                                'step_title' => $step['step_title'],
                                'step_description' => $step['step_description'],
                                'step_order' => $index + 1,
                            ]);

                            // Store step files if any
                            if (isset($step['files']) && is_array($step['files'])) {
                                foreach ($step['files'] as $file) {
                                    $directory = 'walkthrough_steps/' . $walkthroughStep->id;
                                    store_file_media($file, $walkthroughStep, $directory);
                                }
                            }
                        }
                    }
                }
            });

            toast('Functionality Walkthrough updated successfully.', 'success');
            return redirect()->route('administration.functionality_walkthrough.show', ['functionalityWalkthrough' => $functionalityWalkthrough]);
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while updating the walkthrough: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        try {
            $functionalityWalkthrough->delete();

            toast('Functionality Walkthrough deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
