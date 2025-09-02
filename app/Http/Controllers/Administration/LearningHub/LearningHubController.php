<?php

namespace App\Http\Controllers\Administration\LearningHub;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\LearningHub\LearningHub;
use App\Http\Requests\Administration\LearningHub\LearningTopicStoreRequest;
use App\Http\Requests\Administration\LearningHub\LearningTopicUpdateRequest;
use App\Services\Administration\LearningHub\LearningHubService;

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
        $query = LearningHub::with(['creator']);

        // Filter by creator
        if ($request->filled('creator_id')) {
            $query->where('creator_id', $request->creator_id);
        }

        // Filter by month/year
        if ($request->filled('created_month_year')) {
            $monthYear = \Carbon\Carbon::parse($request->created_month_year);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        }

        $learning_topics = $query->latest()->get();

        // Get roles for filter dropdown
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.learning_hub.index', compact('learning_topics', 'roles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $learning_topics = LearningHub::with([
            'creator.employee',
            'creator.media',
            'creator.roles'
        ])->get()->filter(function ($hub) {
            return $hub->isAuthorized();
        });
        // dd($learning_topics);
        return view('administration.learning_hub.my', compact(['learning_topics']));
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

        return view('administration.learning_hub.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LearningTopicStoreRequest $request)
    {
        try {
            $data = $request->validated();

            // Add files to data if present
            if ($request->hasFile('files')) {
                $data['files'] = $request->file('files');
            }

            $this->learningHubService->createLearningTopic($data);

            toast('Learning Topic assigned successfully.', 'success');
            return redirect()->route('administration.learning_hub.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the learning topic: ' . $e->getMessage());
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
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.learning_hub.edit', compact(['learning_topic', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearningTopicUpdateRequest $request, LearningHub $learning_topic)
    {
        try {
            $data = $request->validated();

            // Add files to data if present
            if ($request->hasFile('edit_files')) {
                $data['files'] = $request->file('edit_files');
            }

            $this->learningHubService->updateLearningTopic($learning_topic, $data);

            toast('Learning Topic updated successfully.', 'success');
            return redirect()->route('administration.learning_hub.show', $learning_topic);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the learning topic: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningHub $learning_topic)
    {
        try {
            // Check if user is the creator
            if ($learning_topic->creator_id !== auth()->id()) {
                return redirect()->back()->with('error', 'You can only delete your own learning topics.');
            }

            $this->learningHubService->deleteLearningTopic($learning_topic);

            toast('Learning Topic deleted successfully.', 'success');
            return redirect()->route('administration.learning_hub.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the learning topic: ' . $e->getMessage());
        }
    }
}
