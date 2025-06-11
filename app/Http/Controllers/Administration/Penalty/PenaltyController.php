<?php

namespace App\Http\Controllers\Administration\Penalty;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Penalty\Penalty;
use App\Models\Attendance\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Administration\Penalty\PenaltyService;
use App\Http\Requests\Administration\Penalty\PenaltyStoreRequest;

class PenaltyController extends Controller
{
    protected $penaltyService;

    public function __construct(PenaltyService $penaltyService)
    {
        $this->penaltyService = $penaltyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        // Get users for filtering
        $users = User::with(['roles', 'media', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->select(['id', 'name'])
            ->get();

        // Get penalties with necessary relationships
        $penalties = Penalty::with([
                'user.employee',
                'user.media',
                'user.roles',
                'attendance'
            ])
            ->whereIn('user_id', $userIds)
            ->orderByDesc('created_at')
            ->get();

        return view('administration.penalty.index', compact(['users', 'penalties']));
    }

    /**
     * Display penalties for the authenticated user.
     */
    public function my(Request $request)
    {
        // Get penalties for the authenticated user only
        $penalties = Penalty::with([
                'user.employee',
                'user.media',
                'user.roles',
                'attendance',
                'creator.employee',
                'creator.media',
                'creator.roles',
            ])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('administration.penalty.my', compact(['penalties']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        // Get users for employee selection
        $users = User::with(['employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->select(['id', 'name'])
            ->get();

        // Get penalty types
        $penaltyTypes = Penalty::getPenaltyTypes();

        return view('administration.penalty.create', compact(['users', 'penaltyTypes']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PenaltyStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $penalty = $this->penaltyService->store($request->validated());

                // Store penalty proof files if uploaded
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'penalties/' . $penalty->id;
                        store_file_media($file, $penalty, $directory);
                    }
                }
            });

            toast('Penalty Created Successfully.', 'success');
            return redirect()->route('administration.penalty.index');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Penalty $penalty)
    {
        $penalty->load([
            'user.employee',
            'user.media',
            'user.roles',
            'attendance',
            'creator.employee',
            'creator.roles',
            'files'
        ]);

        return view('administration.penalty.show', compact(['penalty']));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penalty $penalty)
    {
        try {
            $penalty->delete();

            toast('Penalty Deleted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }




    /**
     * Get attendances for a specific user on today's date (AJAX endpoint)
     */
    public function getAttendances(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $today = Carbon::today()->format('Y-m-d');

        $attendances = Attendance::where('user_id', $request->user_id)
            ->where('clock_in_date', $today)
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'text' => $attendance->type . ' Clock In: ' . show_time($attendance->clock_in) .
                             ($attendance->clock_out ? ' - Clock Out: ' . show_time($attendance->clock_out) : ' (Ongoing)')
                ];
            });

        return response()->json($attendances, 200);
    }
}
