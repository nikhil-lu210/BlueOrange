<?php

namespace App\Http\Controllers\Administration\LearningHub;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LearningHub\LearningHub;
use App\Services\Administration\LearningHub\LearningHubService;
use App\Http\Requests\Administration\LearningHub\LearningTopicStoreRequest;
use App\Http\Requests\Administration\LearningHub\LearningTopicUpdateRequest;

class LearningHubController extends Controller
{
    protected $learningHubService;

    public function __construct(LearningHubService $learningHubService)
    {
        $this->learningHubService = $learningHubService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $learning_topics = LearningHub::query()
            ->withCreatorDetails()
            ->byCreator($request->creator_id)
            ->byMonthYear($request->created_month_year)
            ->latest()
            ->get();

        $roles = LearningHub::getRolesForRecipients();

        return view('administration.learning_hub.index', compact('learning_topics', 'roles'));
    }

    /**
     * Display user's learning topics.
     */
    public function my()
    {
        $learning_topics = LearningHub::query()
            ->withCreatorDetails()
            ->get()
            ->filter(fn($hub) => $hub->isAuthorized());

        return view('administration.learning_hub.my', compact('learning_topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = LearningHub::getRolesForRecipients();

        return view('administration.learning_hub.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LearningTopicStoreRequest $request)
    {
        try {
            $data = $this->prepareData($request);
            $this->learningHubService->createLearningTopic($data);

            toast('Learning Topic assigned successfully.', 'success');
            return redirect()->route('administration.learning_hub.index');
        } catch (Exception $e) {
            return $this->handleError($e, 'creating');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningHub $learning_topic)
    {
        $learning_topic->updateReadByAt(auth()->id());

        return view('administration.learning_hub.show', compact('learning_topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningHub $learning_topic)
    {
        $roles = LearningHub::getRolesForRecipients();

        return view('administration.learning_hub.edit', compact('learning_topic', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearningTopicUpdateRequest $request, LearningHub $learning_topic)
    {
        try {
            $data = $this->prepareData($request, 'edit_files');
            $this->learningHubService->updateLearningTopic($learning_topic, $data);

            toast('Learning Topic updated successfully.', 'success');
            return redirect()->route('administration.learning_hub.show', $learning_topic);
        } catch (Exception $e) {
            return $this->handleError($e, 'updating');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningHub $learning_topic)
    {
        try {
            if (!$learning_topic->canDelete(auth()->id())) {
                return redirect()->back()->with('error', 'You can only delete your own learning topics.');
            }

            $this->learningHubService->deleteLearningTopic($learning_topic);

            toast('Learning Topic deleted successfully.', 'success');
            return redirect()->route('administration.learning_hub.index');
        } catch (Exception $e) {
            return $this->handleError($e, 'deleting');
        }
    }

    /**
     * Prepare data for service methods
     */
    private function prepareData(Request $request, string $fileKey = 'files'): array
    {
        $data = $request->validated();

        if ($request->hasFile($fileKey)) {
            $data['files'] = $request->file($fileKey);
        }

        return $data;
    }

    /**
     * Handle errors consistently
     */
    private function handleError(Exception $e, string $action): \Illuminate\Http\RedirectResponse
    {
        return redirect()->back()
            ->withInput()
            ->with('error', "An error occurred while {$action} the learning topic: " . $e->getMessage());
    }
}
