<?php

namespace App\Models\Quiz\QuizTest;

use Illuminate\Support\Str;
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

    protected $cascadeDeletes = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'auto_submitted' => 'boolean',
    ];

    protected $fillable = [
        'creator_id',
        'candidate_name',
        'candidate_email',
        'total_questions',
        'total_time',
        'passing_score',
        'started_at',
        'ended_at',
        'attempted_questions',
        'total_score',
        'auto_submitted',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($test) {
            // Combine 'SIQT', timestamp
            $test->testid = 'SIQT' . now()->format('Ymd') . strtoupper(Str::random(4));

            // Store the test creator id
            if (auth()->check()) {
                $test->creator_id = auth()->user()->id;
            }
        });
    }
}
