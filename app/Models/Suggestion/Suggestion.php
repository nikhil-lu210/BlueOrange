<?php

namespace App\Models\Suggestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Suggestion\Mutators\SuggestionMutators;
use App\Models\Suggestion\Accessors\SuggestionAccessors;
use App\Models\Suggestion\Relations\SuggestionRelations;
use App\Models\Suggestion\Scopes\SuggestionScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\Suggestion\SuggestionObserver;
use App\Traits\HasCustomRouteId;
use Dyrynda\Database\Support\CascadeSoftDeletes;

#[ObservedBy([SuggestionObserver::class])]
class Suggestion extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use SuggestionRelations;

    // Accessors & Mutators
    use SuggestionAccessors, SuggestionMutators;

    // Scopes
    use SuggestionScopes;

    protected $casts = [];

    protected $fillable = [
        'user_id',
        'type',
        'module',
        'title',
        'message'
    ];
}