<?php

namespace App\Http\Controllers\Administration\Hiring;

use Exception;
use Illuminate\Http\Request;
use App\Models\Hiring\HiringStage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Hiring\HiringCandidate;
use App\Services\Administration\Hiring\HiringService;

use App\Http\Requests\Administration\Hiring\HiringCompleteRequest;
use App\Http\Requests\Administration\Hiring\HiringCandidateStoreRequest;
use App\Http\Requests\Administration\Hiring\HiringCandidateUpdateRequest;


class HiringController extends Controller
{
    protected $hiringService;

    public function __construct(HiringService $hiringService)
    {
        $this->hiringService = $hiringService;
    }

    /**
     * Display a listing of hiring candidates
     */
    public function index(Request $request)
    {
        $candidates = $this->hiringService->getCandidatesQuery($request)->paginate(15);

        $stages = HiringStage::active()->ordered()->get();
        $statuses = HiringCandidate::getStatuses();

        return view('administration.hiring.index', compact('candidates', 'stages', 'statuses'));
    }

    /**
     * Show the form for creating a new candidate
     */
    public function create()
    {
        $evaluators = $this->hiringService->getAvailableEvaluators();
        return view('administration.hiring.create', compact('evaluators'));
    }

    /**
     * Store a newly created candidate
     */
    public function store(HiringCandidateStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request, &$candidate) {
                $candidate = $this->hiringService->storeCandidate($request->validated());

                // Store resume file
                if ($request->hasFile('resume')) {
                    $directory = 'hiring/candidates/' . $candidate->id . '/resume';
                    store_file_media($request->file('resume'), $candidate, $directory, 'Resume');
                }

                // Store additional files if uploaded
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'hiring/candidates/' . $candidate->id . '/documents';
                        store_file_media($file, $candidate, $directory);
                    }
                }

                // Create stage evaluations based on assignments
                $this->hiringService->createStageEvaluations($candidate, $request->all());
            });

            toast('Candidate ' . $candidate->name . ' added successfully with stage assignments.', 'success');
            return redirect()->route('administration.hiring.show', ['hiring_candidate' => $candidate]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified candidate
     */
    public function show(HiringCandidate $hiring_candidate)
    {
        $hiring_candidate->load([
            'creator.employee',
            'user.employee',
            'evaluations.stage',
            'evaluations.assignedUser.employee',
            'evaluations.creator.employee',
            'evaluations.updater.employee',
            'files'
        ]);

        $stages = HiringStage::active()->ordered()->get();
        $evaluators = $this->hiringService->getAvailableEvaluators();

        return view('administration.hiring.show', compact('hiring_candidate', 'stages', 'evaluators'));
    }

    /**
     * Show the form for editing the specified candidate
     */
    public function edit(HiringCandidate $hiring_candidate)
    {
        return view('administration.hiring.edit', compact('hiring_candidate'));
    }

    /**
     * Update the specified candidate
     */
    public function update(HiringCandidateUpdateRequest $request, HiringCandidate $hiring_candidate)
    {
        try {
            DB::transaction(function () use ($request, $hiring_candidate) {
                $this->hiringService->updateCandidate($hiring_candidate, $request->validated());

                // Store additional files if uploaded
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'hiring/candidates/' . $hiring_candidate->id . '/documents';
                        store_file_media($file, $hiring_candidate, $directory);
                    }
                }
            });

            toast('Candidate updated successfully.', 'success');
            return redirect()->route('administration.hiring.show', ['hiring_candidate' => $hiring_candidate]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified candidate
     */
    public function destroy(HiringCandidate $hiring_candidate)
    {
        try {
            $hiring_candidate->delete();
            toast('Candidate deleted successfully.', 'success');
            return redirect()->route('administration.hiring.index');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Show my assigned evaluations
     */
    public function myEvaluations(Request $request)
    {
        $evaluations = $this->hiringService->getMyEvaluationsQuery(auth()->id())->paginate(15);

        return view('administration.hiring.my_evaluations', compact('evaluations'));
    }

    /**
     * Store or update stage evaluation
     */
    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:hiring_stage_evaluations,id',
            'status' => 'required|in:pending,in_progress,completed,passed,failed',
            'notes' => 'nullable|string|max:2000',
            'feedback' => 'nullable|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:10',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function () use ($request, &$evaluation) {
                $evaluation = \App\Models\Hiring\HiringStageEvaluation::findOrFail($request->evaluation_id);

                // Update evaluation
                $evaluation->update([
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'feedback' => $request->feedback,
                    'rating' => $request->rating,
                    'updated_by' => auth()->id(),
                ]);

                // Update timestamps based on status
                $this->hiringService->updateEvaluationTimestamps($evaluation, $request->status);

                // Store evaluation files if uploaded
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'hiring/evaluations/' . $evaluation->id;
                        store_file_media($file, $evaluation, $directory);
                    }
                }

                // Auto-progress candidate based on evaluation result
                if (in_array($request->status, ['passed', 'failed'])) {
                    $candidate = $evaluation->candidate;
                    $this->hiringService->checkAndProgressCandidate($candidate, $request->status);
                }
            });

            toast('Evaluation updated successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show hiring completion form
     */
    public function showHiringForm(HiringCandidate $hiring_candidate)
    {
        // Check if candidate is eligible for hiring
        if ($hiring_candidate->status !== 'in_progress' || $hiring_candidate->current_stage < 3) {
            alert('Error', 'Candidate must complete all stages before hiring.', 'error');
            return redirect()->back();
        }

        $roles = \Spatie\Permission\Models\Role::all();

        return view('administration.hiring.complete', compact('hiring_candidate', 'roles'));
    }

    /**
     * Complete hiring process
     */
    public function completeHiring(HiringCompleteRequest $request, HiringCandidate $hiring_candidate)
    {
        try {
            $user = $this->hiringService->completeHiring($hiring_candidate, $request->validated());

            toast('Candidate hired successfully! User account created for ' . $user->name, 'success');
            return redirect()->route('administration.hiring.show', ['hiring_candidate' => $hiring_candidate]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Reject candidate
     */
    public function reject(Request $request, HiringCandidate $hiring_candidate)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000'
        ]);

        try {
            $this->hiringService->rejectCandidate($hiring_candidate, $request->reason);

            toast('Candidate rejected.', 'info');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
