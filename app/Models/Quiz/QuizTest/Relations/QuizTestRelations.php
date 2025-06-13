<?php

namespace App\Models\Quiz\QuizTest\Relations;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Quiz\QuizAnswer\QuizAnswer;
use App\Models\Quiz\QuizQuestion\QuizQuestion;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait QuizTestRelations
{
    /**
     * Get the user who created the test.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all answers submitted for this test.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_test_id');
    }

    /**
     * All quiz questions in this test.
     *
     * This is not a standard Eloquent relation but acts as a helper method
     * returning a Collection of QuizQuestion models.
     */
    public function questions(): Collection
    {
        if (empty($this->question_ids)) {
            return collect();
        }

        return QuizQuestion::whereIn('id', $this->question_ids)->get();
    }

    /**
     * Get answered questions only (helper).
     */
    public function answered_questions(): Collection
    {
        return QuizQuestion::whereIn('id', $this->answers()->pluck('quiz_question_id'))->get();
    }
}
