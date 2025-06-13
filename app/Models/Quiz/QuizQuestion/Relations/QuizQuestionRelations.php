<?php

namespace App\Models\Quiz\QuizQuestion\Relations;

use App\Models\User;
use App\Models\Quiz\QuizTest\QuizTest;
use App\Models\Quiz\QuizAnswer\QuizAnswer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait QuizQuestionRelations
{
    /**
     * Get the user who created the question.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all answers submitted for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_question_id');
    }

    /**
     * All quiz tests in which this question appears.
     *
     * Because the question IDs are stored in a JSON column (`quiz_tests.question_ids`)
     * instead of a traditional pivot table, we expose this as a convenience method
     * that returns a Collection (not a true Eloquent relationship).
     */
    public function tests(): Collection
    {
        return QuizTest::query()
            ->whereJsonContains('question_ids', $this->id)
            ->get();
    }
}
