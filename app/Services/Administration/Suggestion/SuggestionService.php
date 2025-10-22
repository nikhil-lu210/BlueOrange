<?php

namespace App\Services\Administration\Suggestion;

use App\Models\Suggestion\Suggestion;
use App\Models\User;

class SuggestionService
{
    public function getAllSuggestion($request){
        $query = Suggestion::with(['user']);
        // Apply filters
        if ($request->filled('user_id')) {
            $query->filterByUser($request->user_id);
        }

        if ($request->filled('type')) {
            $query->filterByType($request->type);
        }

        if ($request->filled('module')) {
            $query->filterByModule($request->module);
        }

        $suggestions = $query->latest()->paginate(100);
        return $suggestions;
    }

    public function getMySuggestion($request){
        $query = Suggestion::with(['user'])->filterByUser(auth()->id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $suggestions = $query->latest()->paginate(100);
        return $suggestions;
    }

    public function getActiveUsers(){
        return User::where('status', 'Active')->get();
    }

    public function storeSuggestion($request){
        return Suggestion::create([
            'user_id' => auth()->id(),
            'type' => $request['type'],
            'module' => $request['module'],
            'title' => $request['title'],
            'message' => $request['message'],
        ]);
    }
}