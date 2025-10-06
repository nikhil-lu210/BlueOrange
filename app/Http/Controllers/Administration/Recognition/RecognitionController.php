<?php

namespace App\Http\Controllers\Administration\Recognition;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Recognition\Recognition;
use App\Models\User;
use App\Services\Administration\Recognition\RecognitionService;
use App\Http\Requests\Recognition\StoreRecognitionRequest;
use App\Http\Requests\Recognition\UpdateRecognitionRequest;
use App\Notifications\Administration\Recognition\RecognitionCreatedNotification;
use App\Exports\Administration\Recognition\RecognitionReportExport;
use Maatwebsite\Excel\Facades\Excel;

class RecognitionController extends Controller
{
    protected $recognitionService;

    public function __construct(RecognitionService $recognitionService)
    {
        $this->recognitionService = $recognitionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Recognition::class);

        $query = Recognition::with(['user', 'recognizer']);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('recognizer_id')) {
            $query->where('recognizer_id', $request->recognizer_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_score')) {
            $query->where('total_mark', '>=', $request->min_score);
        }

        if ($request->filled('max_score')) {
            $query->where('total_mark', '<=', $request->max_score);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $recognitions = $query->latest()->paginate(100);

        // Get filter data
        $users = User::where('status', 'Active')->get();
        $categories = config('recognition.categories');

        return view('administration.recognition.index', compact('recognitions', 'users', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Recognition::class);

        $users = auth()->user()->tl_employees ?? collect();
        $categories = config('recognition.categories');

        return view('administration.recognition.create', compact('users', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecognitionRequest $request)
    {
        $this->authorize('create', Recognition::class);

        Recognition::create([
            'user_id' => $request->user_id,
            'category' => $request->category,
            'total_mark' => $request->total_mark,
            'comment' => $request->comment,
            'recognizer_id' => auth()->id(),
        ]);

        toast('Recognition Submitted Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Recognition $recognition)
    {
        $this->authorize('view', $recognition);

        $recognition->load(['user', 'recognizer']);
        
        return view('administration.recognition.show', compact('recognition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recognition $recognition)
    {
        $this->authorize('update', $recognition);

        $categories = config('recognition.categories');
        
        return view('administration.recognition.edit', compact('recognition', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecognitionRequest $request, Recognition $recognition)
    {
        $this->authorize('update', $recognition);

        $recognition->update($request->validated());

        toast('Recognition Updated Successfully', 'success');
        return redirect()->route('administration.recognition.show', $recognition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recognition $recognition)
    {
        $this->authorize('delete', $recognition);

        $recognition->delete();

        toast('Recognition Deleted Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Display user's own recognitions.
     */
    public function my(Request $request)
    {
        $this->authorize('viewAny', Recognition::class);

        $query = Recognition::with(['user', 'recognizer'])
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_score')) {
            $query->where('total_mark', '>=', $request->min_score);
        }

        if ($request->filled('max_score')) {
            $query->where('total_mark', '<=', $request->max_score);
        }

        $recognitions = $query->latest()->paginate(100);
        $categories = config('recognition.categories');

        return view('administration.recognition.my', compact('recognitions', 'categories'));
    }

    /**
     * Get recognition analytics.
     */
    public function analytics()
    {
        $this->authorize('viewAny', Recognition::class);

        $analytics = $this->recognitionService->getDashboardAnalytics(auth()->user());
        
        return view('administration.recognition.analytics', compact('analytics'));
    }

    /**
     * Get recognition leaderboard.
     */
    public function leaderboard(Request $request)
    {
        $this->authorize('viewAny', Recognition::class);

        $category = $request->get('category');
        $limit = $request->get('limit', 10);
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if ($category) {
            $topPerformers = $this->recognitionService->getCategoryLeaderboard($category, $limit, $dateFrom, $dateTo);
        } else {
            $topPerformers = $this->recognitionService->getTopPerformers($limit, $dateFrom, $dateTo);
        }

        $categories = config('recognition.categories');

        return view('administration.recognition.leaderboard', compact('topPerformers', 'categories', 'category'));
    }

    /**
     * Export recognition report to Excel.
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Recognition::class);

        $query = Recognition::with(['user', 'recognizer']);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('recognizer_id')) {
            $query->where('recognizer_id', $request->recognizer_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_score')) {
            $query->where('total_mark', '>=', $request->min_score);
        }

        if ($request->filled('max_score')) {
            $query->where('total_mark', '<=', $request->max_score);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $recognitions = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'recognition_report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new RecognitionReportExport($recognitions),
            $fileName
        );
    }

    public function markRecognizeAsRead()
    {
        Auth::user()
            ->notifications()
            ->where('type', RecognitionCreatedNotification::class)
            ->whereNull('read_at') // mark only unread
            ->update(['read_at' => now()]);

        toast('Recognition Notification(s) has been marked as read.', 'success');
        return redirect()->back();
    }
}
