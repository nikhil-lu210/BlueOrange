<?php

namespace App\Models\QuizAnswer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\QuizAnswer\Mutators\QuizAnswerMutators;
use App\Models\QuizAnswer\Accessors\QuizAnswerAccessors;
use App\Models\QuizAnswer\Relations\QuizAnswerRelations;
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