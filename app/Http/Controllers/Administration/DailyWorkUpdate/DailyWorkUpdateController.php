<?php

namespace App\Http\Controllers\Administration\DailyWorkUpdate;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Http\Requests\Administration\DailyWorkUpdate\DailyWorkUpdateStoreRequest;
use App\Mail\Administration\DailyWorkUpdate\DailyWorkUpdateRequestMail;
use App\Notifications\Administration\DailyWorkUpdate\DailyWorkUpdateCreateNotification;
use App\Notifications\Administration\DailyWorkUpdate\DailyWorkUpdateUpdateNotification;

class DailyWorkUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(auth()->user()->tl_employees);
        $userIds = auth()->user()->user_interactions->pluck('id');


        $teamLeaders = User::whereIn('id', $userIds)
                            ->whereStatus('Active')
                            ->get()
                            ->filter(function ($user) {
                                return $user->hasAnyPermission(['Daily Work Update Everything', 'Daily Work Update Update']);
                            });

        $roles = $this->getRolesWithPermission();

        $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request);

        return view('administration.daily_work_update.index', compact('teamLeaders', 'roles', 'dailyWorkUpdates'));
    }

    /**
     * Display my work updates
     */
    public function my(Request $request)
    {
        // dd(auth()->user()->tl_employees);
        $roles = Role::select(['id', 'name'])->with(['users' => function ($query) {
                            $query->permission('Daily Work Update Create')
                                ->select(['id', 'name'])
                                ->whereIn('id', auth()->user()->tl_employees->pluck('id'))
                                ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                                ->whereStatus('Active');
                        }])->get();

        $authUserID = auth()->id();

        if (!$request->has('filter_work_updates') && auth()->user()->tl_employees_daily_work_updates->count() < 1) {
            $dailyWorkUpdates = DailyWorkUpdate::whereUserId($authUserID)
                                ->orWhere('team_leader_id', $authUserID)
                                ->orderByDesc('created_at')
                                ->get();
        } else {
            $dailyWorkUpdates = $this->getFilteredDailyWorkUpdates($request, $authUserID);
        }

        return view('administration.daily_work_update.my', compact('roles', 'dailyWorkUpdates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.daily_work_update.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DailyWorkUpdateStoreRequest $request)
    {
        // dd($request->all(), auth()->user()->active_team_leader->id);
        $authUser = auth()->user();
        $teamLeader = $authUser->active_team_leader;

        if (is_null($teamLeader)) {
            return redirect()->back()->withInput()->withErrors('Team Leader is Missing. Please ask authority to assign your Team Leader');
        }

        try {
            DB::transaction(function () use ($request, $authUser, $teamLeader) {
                $workUpdate = DailyWorkUpdate::create([
                    'user_id' => $authUser->id,
                    'team_leader_id' => $teamLeader->id,
                    'date' => $request->date ?? date('Y-m-d'),
                    'work_update' => $request->work_update,
                    'progress' => $request->progress,
                    'note' => $request->note_issue ?? null
                ]);

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'daily_work_update/' . $authUser->userid;
                        store_file_media($file, $workUpdate, $directory);
                    }
                }

                // Send Notification to System
                $teamLeader->notify(new DailyWorkUpdateCreateNotification($workUpdate, auth()->user()));

                // Send Mail to the Team Leader
                Mail::to($teamLeader->employee->official_email)->send(new DailyWorkUpdateRequestMail($workUpdate, $teamLeader));
            });

            toast('Daily Work Update Has Been Submitted Successfully.', 'success');
            return redirect()->route('administration.daily_work_update.my');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyWorkUpdate $dailyWorkUpdate)
    {
        // dd($dailyWorkUpdate);
        return view('administration.daily_work_update.show', compact(['dailyWorkUpdate']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyWorkUpdate $dailyWorkUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyWorkUpdate $dailyWorkUpdate)
    {
        // dd($request->all(), $comment);
        try {
            DB::transaction(function () use ($request, $dailyWorkUpdate) {
                $comment = $request->input('comment');
                // Check if the comment contains only empty tags or line breaks
                if (trim(strip_tags($comment)) === '') {
                    $comment = null; // Or handle it as an empty comment
                }

                $dailyWorkUpdate->update([
                    'rating' => $request->rating,
                    'comment' => $comment
                ]);

                // Send Notification to System
                $dailyWorkUpdate->user->notify(new DailyWorkUpdateUpdateNotification($dailyWorkUpdate, auth()->user()));
            });

            toast('Daily Work Update Has Been Rated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyWorkUpdate $dailyWorkUpdate)
    {
        try {
            $dailyWorkUpdate->forceDelete();

            toast('Daily Work Updated Has Been Deleted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Helper method to get roles with 'Daily Work Update Create' permission
     */
    private function getRolesWithPermission()
    {
        return Role::select(['id', 'name'])
            ->with(['users' => function ($user) {
                $user->permission('Daily Work Update Create')
                    ->select(['id', 'name'])
                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                    ->whereStatus('Active');
            }])
            ->get();
    }

    /**
     * Helper method to filter Daily Work Updates
     */
    private function getFilteredDailyWorkUpdates(Request $request, $teamLeaderId = null)
    {
        $query = DailyWorkUpdate::query()->orderByDesc('created_at');

        if ($teamLeaderId) {
            $query->where('team_leader_id', $teamLeaderId);
        }

        if ($request->filled('team_leader_id')) {
            $query->where('team_leader_id', $request->team_leader_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('created_month_year')) {
            $monthYear = Carbon::createFromFormat('F Y', $request->created_month_year);
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_work_updates')) {
            // Default to current month
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }

        if ($request->filled('status')) {
            $request->status === 'Reviewed' ? $query->whereNotNull('rating') : $query->whereNull('rating');
        }

        return $query->get();
    }
}
