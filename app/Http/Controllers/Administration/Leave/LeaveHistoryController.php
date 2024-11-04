<?php

namespace App\Http\Controllers\Administration\Leave;

use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Leave\LeaveApprovalRequest;
use App\Models\Leave\LeaveAvailable;
use App\Services\Administration\Leave\LeaveHistoryService;
use App\Http\Requests\Administration\Leave\LeaveHistoryStoreRequest;

class LeaveHistoryController extends Controller
{
    protected $leaveHistoryService;

    public function __construct(LeaveHistoryService $leaveHistoryService)
    {
        $this->leaveHistoryService = $leaveHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        // Eager load all necessary relationships
        $users = User::with(['roles', 'media', 'shortcuts', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->get(['id', 'name']);

        // Get daily breaks with the pre-loaded users
        $leaves = $this->leaveHistoryService->getLeavesQuery($request)
            ->whereIn('user_id', $userIds)
            ->get();

        return view('administration.leave.index', compact(['users', 'leaves']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        // Get daily breaks with the pre-loaded users
        $leaves = $this->leaveHistoryService->getLeavesQuery($request, auth()->user()->id)->get();

        return view('administration.leave.my', compact(['leaves']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $oldLeaveDaysCount = count(old('leave_days.date', []));
        return view('administration.leave.create', compact('oldLeaveDaysCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveHistoryStoreRequest $request)
    {
        // dd($request->hasFile('files'));
        try {
            $user = Auth::user();
            $this->leaveHistoryService->store($user, $request->validated());

            toast('Leave Application Submitted Successfully.', 'success');
            return redirect()->route('administration.leave.history.my');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to send leave request. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveHistory $leaveHistory)
    {
        // dd($leaveHistory);
        return view('administration.leave.show', compact(['leaveHistory']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function approve(LeaveApprovalRequest $request, LeaveHistory $leaveHistory)
    {
        $this->leaveHistoryService->approve($request, $leaveHistory);
        
        toast('Leave request approved and leave balance updated successfully.', 'success');
        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     */
    public function reject(Request $request, LeaveHistory $leaveHistory)
    {
        // dd($request->all(), $leaveHistory->toArray());
        $request->validate([
            'reviewer_note' => ['required', 'string'],
        ]);

        try {
            $leaveHistory->update([
                'reviewer_note' => $request->reviewer_note,
                'status' => 'Rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => Carbon::now(),
            ]);

            toast('Leave request rejected successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            throw new Exception('Failed to reject leave: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveHistory $leaveHistory)
    {
        //
    }



    /**
     * Build the query for retrieving daily breaks.
     *
     * @param Request $request
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getLeavesQuery(Request $request, int $userId = null)
    {
        $query = LeaveHistory::with([
                                'user:id,userid,name', 
                                'user.media', 
                                'user.roles'
                            ])
                            ->orderByDesc('date')
                            ->orderBy('created_at');

        // Apply user ID filter if provided
        if ($userId) {
            $query->whereUserId($userId);
        }

        // Apply user ID filter if request user_id provided
        if ($request->user_id) {
            $query->whereUserId($request->user_id);
        }

        // Handle month/year filtering
        if ($request->has('leave_month_year') && !is_null($request->leave_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->leave_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            // Default to current month if no specific filter is applied
            if (!$request->has('filter_leaves')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        return $query;
    }
}
