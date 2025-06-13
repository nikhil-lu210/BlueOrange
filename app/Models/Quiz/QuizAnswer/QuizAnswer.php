<?php

namespace App\Models\Quiz\QuizAnswer;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quiz\QuizAnswer\Mutators\QuizAnswerMutators;
use App\Models\Quiz\QuizAnswer\Accessors\QuizAnswerAccessors;
use App\Models\Quiz\QuizAnswer\Relations\QuizAnswerRelations;

class QuizAnswer extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use QuizAnswerRelations;

    // Accessors & Mutators
    use QuizAnswerAccessors, QuizAnswerMutators;

    protected $cascadeDeletes = [];

    protected $casts = [];

    protected $fillable = [];
}
