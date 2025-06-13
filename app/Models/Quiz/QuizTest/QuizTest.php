<?php

namespace App\Models\Quiz\QuizTest;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quiz\QuizTest\Mutators\QuizTestMutators;
use App\Models\Quiz\QuizTest\Accessors\QuizTestAccessors;
use App\Models\Quiz\QuizTest\Relations\QuizTestRelations;

class QuizTest extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use QuizTestRelations;

    // Accessors & Mutators
    use QuizTestAccessors, QuizTestMutators;

    protected $cascadeDeletes = ['answers'];

    protected $casts = [];

    protected $fillable = [];
}
