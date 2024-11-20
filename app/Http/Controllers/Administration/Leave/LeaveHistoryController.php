<?php

namespace App\Http\Controllers\Administration\Leave;

use App\Exports\Administration\Leave\LeaveExport;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveHistory;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Administration\Leave\LeaveExportService;
use App\Services\Administration\Leave\LeaveHistoryService;
use App\Http\Requests\Administration\Leave\LeaveApprovalRequest;
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
        // dd($request->all());
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
     * export leaves.
     */
    public function export(Request $request, LeaveExportService $leaveExportService)
    {
        try {
            $exportData = $leaveExportService->export($request);

            if (is_null($exportData)) {
                toast('There are no leaves to download.', 'warning');
                return redirect()->back();
            }

            // Return the Excel download with the appropriate filename
            return Excel::download(new LeaveExport($exportData['leaves']), $exportData['fileName']);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
