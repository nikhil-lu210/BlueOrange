<?php

namespace App\Http\Controllers\Administration\Leave;

use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Administration\Leave\LeaveExport;
use App\Services\Administration\Leave\LeaveExportService;
use App\Services\Administration\Leave\LeaveHistoryService;
use App\Services\Administration\Leave\LeaveValidationService;
use App\Mail\Administration\Leave\LeaveRequestStatusUpdateMail;
use App\Http\Requests\Administration\Leave\LeaveApprovalRequest;
use App\Http\Requests\Administration\Leave\LeaveHistoryStoreRequest;
use App\Notifications\Administration\Leave\LeaveRequestUpdateNotification;

class LeaveHistoryController extends Controller
{
    protected $leaveHistoryService;
    protected $leaveValidationService;

    public function __construct(LeaveHistoryService $leaveHistoryService, LeaveValidationService $leaveValidationService)
    {
        $this->leaveHistoryService = $leaveHistoryService;
        $this->leaveValidationService = $leaveValidationService;

        // Apply policy authorization to resource methods
        $this->authorizeResource(LeaveHistory::class, 'leaveHistory', [
            'except' => ['index', 'my', 'create', 'store', 'export']
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', LeaveHistory::class);

        $userIds = auth()->user()->user_interactions->pluck('id');

        // Optimize team leaders query
        $teamLeaders = User::with(['permissions', 'roles', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->get();

        // Optimize users query
        $users = User::with(['roles', 'media', 'shortcuts', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->select(['id', 'name'])
            ->get();

        // Get leave histories
        $leaves = $this->leaveHistoryService->getLeaveHistories($request)
            ->whereIn('user_id', $userIds)
            ->get();

        // Log access
        Log::info('Leave histories accessed', [
            'user_id' => auth()->id(),
            'team_size' => $userIds->count(),
            'ip_address' => request()->ip()
        ]);

        return view('administration.leave.index', compact(['teamLeaders', 'users', 'leaves']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        // Auto-sync leave balances for the current user and year
        $this->autoSyncLeaveBalances();

        $leaves = $this->leaveHistoryService->getLeaveHistories($request)->where('user_id', auth()->user()->id)->get();

        return view('administration.leave.my', compact(['leaves']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', LeaveHistory::class);

        // Auto-sync leave balances for the current user and year
        $this->autoSyncLeaveBalances();

        $oldLeaveDaysCount = count(old('leave_days.date', []));
        return view('administration.leave.create', compact('oldLeaveDaysCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveHistoryStoreRequest $request)
    {
        // Authorization is handled in the form request

        try {
            $user = Auth::user();

            // Additional server-side validation
            if (!$user->allowed_leave) {
                return redirect()->back()->withErrors([
                    'leave_policy' => 'No active leave policy found. Please contact HR.'
                ]);
            }

            if (!$user->active_team_leader) {
                return redirect()->back()->withErrors([
                    'team_leader' => 'No active team leader found. Leave requests require a team leader for approval.'
                ]);
            }

            $this->leaveHistoryService->store($user, $request->validated());

            // Log successful submission
            Log::info('Leave request submitted', [
                'user_id' => $user->id,
                'leave_type' => $request->input('type'),
                'dates' => $request->input('leave_days.date'),
                'ip_address' => request()->ip()
            ]);

            toast('Leave Application Submitted Successfully.', 'success');
            return redirect()->route('administration.leave.history.my');
        } catch (Exception $e) {
            // Log error
            Log::error('Leave request submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'ip_address' => request()->ip()
            ]);

            return redirect()->back()->withErrors([
                'submission' => 'Failed to submit leave request: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveHistory $leaveHistory)
    {
        // Auto-sync leave balances for the leave owner and year
        $this->autoSyncLeaveBalances($leaveHistory->user_id, Carbon::parse($leaveHistory->date)->year);

        $leaveHistory->load([
            'user.employee',
            'user.media',
            'user.roles',
            'files',
            'reviewer',
            'reviewer.employee',
            'leave_allowed'
        ]);

        return view('administration.leave.show', compact(['leaveHistory']));
    }



    /**
     * Update the specified resource in storage.
     */
    public function approve(LeaveApprovalRequest $request, LeaveHistory $leaveHistory)
    {
        $this->authorize('approve', $leaveHistory);

        try {
            $this->leaveHistoryService->approve($request, $leaveHistory);

            toast('Leave request approved and leave balance updated successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'approval' => 'Failed to approve leave: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function reject(Request $request, LeaveHistory $leaveHistory)
    {
        $this->authorize('reject', $leaveHistory);

        $request->validate([
            'reviewer_note' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        try {
            $leaveHistory->update([
                'reviewer_note' => $request->reviewer_note,
                'status' => 'Rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => Carbon::now(),
            ]);

            // Send Notification to Leave Applier
            $leaveHistory->user->notify(new LeaveRequestUpdateNotification($leaveHistory, auth()->user()));

            // Send Mail to the Leave Applier
            Mail::to($leaveHistory->user->employee->official_email)->queue(new LeaveRequestStatusUpdateMail($leaveHistory, auth()->user()));

            // Log rejection
            Log::info('Leave request rejected', [
                'rejector_id' => auth()->id(),
                'leave_id' => $leaveHistory->id,
                'user_id' => $leaveHistory->user_id,
                'reason' => $request->reviewer_note,
                'ip_address' => request()->ip()
            ]);

            toast('Leave request rejected successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            Log::error('Leave rejection failed', [
                'rejector_id' => auth()->id(),
                'leave_id' => $leaveHistory->id,
                'error' => $e->getMessage(),
                'ip_address' => request()->ip()
            ]);

            return redirect()->back()->withErrors([
                'rejection' => 'Failed to reject leave: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(Request $request, LeaveHistory $leaveHistory)
    {
        $this->authorize('cancel', $leaveHistory);

        $request->validate([
            'reviewer_note' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        try {
            $this->leaveHistoryService->cancel($request, $leaveHistory);

            // Log cancellation
            Log::info('Leave request canceled', [
                'user_id' => auth()->id(),
                'leave_id' => $leaveHistory->id,
                'reason' => $request->reviewer_note,
                'ip_address' => request()->ip()
            ]);

            toast('Leave request has been Canceled Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            Log::error('Leave cancellation failed', [
                'user_id' => auth()->id(),
                'leave_id' => $leaveHistory->id,
                'error' => $e->getMessage(),
                'ip_address' => request()->ip()
            ]);

            return redirect()->back()->withErrors([
                'cancellation' => 'Failed to cancel leave: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Auto-sync leave balances for a user and year.
     * If not provided, defaults to the authenticated user and current year.
     */
    private function autoSyncLeaveBalances(?int $userId = null, ?int $year = null): void
    {
        try {
            $userId = $userId ?? auth()->id();
            $year   = $year ?? now()->year;

            \Artisan::call('leave:sync-balances', [
                '--user-id' => $userId,
                '--year'    => $year,
            ]);

            toast('Leave Balances Synced Successfully for the year ' . $year . ' for ' . show_employee_data($userId, 'alias_name'), 'success');
        } catch (\Exception $e) {
            dd('Failed to auto-sync leave balances. Error: ' . $e->getMessage());
            toast('Failed to auto-sync leave balances. Error: ' . $e->getMessage(), 'error');
        }
    }


    /**
     * export leaves.
     */
    public function export(Request $request, LeaveExportService $leaveExportService)
    {
        $this->authorize('export', LeaveHistory::class);

        try {
            $exportData = $leaveExportService->export($request);

            if (is_null($exportData)) {
                toast('There are no leaves to download.', 'warning');
                return redirect()->back();
            }

            // Log export
            Log::info('Leave data exported', [
                'user_id' => auth()->id(),
                'export_count' => count($exportData['leaves'] ?? []),
                'filename' => $exportData['fileName'],
                'ip_address' => request()->ip()
            ]);

            // Return the Excel download with the appropriate filename
            return Excel::download(new LeaveExport($exportData['leaves']), $exportData['fileName']);
        } catch (Exception $e) {
            Log::error('Leave export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'ip_address' => request()->ip()
            ]);

            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}




