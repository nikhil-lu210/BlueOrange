<?php

namespace App\Http\Controllers\Administration\Suggestion;

use App\Http\Controllers\Controller;
use App\Models\Suggestion\Suggestion;
use App\Services\Administration\Suggestion\SuggestionService;
use App\Http\Requests\Administration\Suggestion\StoreSuggestionRequest;
use Exception;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    protected $suggestionService;

    public function __construct(SuggestionService $suggestionService)
    {
        $this->suggestionService = $suggestionService;
    }
    public function index(Request $request){

        // Get filter data
        $users = $this->suggestionService->getActiveUsers();
        $suggestions = $this->suggestionService->getAllSuggestion($request);
        $types = config('feedback.types');
        $modules = config(('feedback.modules'));
        return view('administration.suggestion.index', compact('suggestions','users', 'types', 'modules'));
    }

    public function my(Request $request){
        $suggestions = $this->suggestionService->getMySuggestion($request);
        $types = config('feedback.types');
        $modules = config(('feedback.modules'));
        return view('administration.suggestion.my', compact('suggestions', 'types', 'modules'));
    }

    public function store(StoreSuggestionRequest $request){
        try {
            $this->suggestionService->storeSuggestion($request->all());
            toast('Suggestion Submitted Successfully', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    public function show(Suggestion $suggestion){
        $suggestion->load(['user']);
        
        return view('administration.suggestion.show', compact('suggestion'));
    }

    public function destroy(Suggestion $suggestion){
        $suggestion->delete();

        toast('Suggestion Deleted Successfully', 'success');
        return redirect()->back();
    }
}
