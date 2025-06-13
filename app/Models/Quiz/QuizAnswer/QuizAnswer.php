<?php

namespace App\Models\Quiz\QuizAnswer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Quiz\QuizAnswer\Mutators\QuizAnswerMutators;
use App\Models\Quiz\QuizAnswer\Accessors\QuizAnswerAccessors;
use App\Models\Quiz\QuizAnswer\Relations\QuizAnswerRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use QuizAnswerRelations;

    // Accessors & Mutators
    use QuizAnswerAccessors, QuizAnswerMutators;

    protected $casts = [];

    protected $fillable = [];
}
