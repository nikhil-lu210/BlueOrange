<?php

namespace App\Models\Quiz\QuizAnswer\Relations;

use App\Models\Quiz\QuizTest\QuizTest;
use App\Models\Quiz\QuizQuestion\QuizQuestion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait QuizAnswerRelations
{
    /**
     * Get the question that this answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    /**
     * Get the test that this answer belongs to.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(QuizTest::class, 'quiz_test_id');
    }
}
