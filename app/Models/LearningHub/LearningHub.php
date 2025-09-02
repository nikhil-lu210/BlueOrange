<?php

namespace App\Models\LearningHub;

use App\Traits\HasCustomRouteId;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\LearningHub\Scopes\LearningHubScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Models\LearningHub\Mutators\LearningHubMutators;
use App\Models\LearningHub\Accessors\LearningHubAccessors;
use App\Models\LearningHub\Relations\LearningHubRelations;
use App\Observers\Administration\LearningHub\LearningHubObserver;

#[ObservedBy([LearningHubObserver::class])]
class LearningHub extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use LearningHubRelations;

    // Accessors & Mutators
    use LearningHubAccessors, LearningHubMutators;

    // Scopes
    use LearningHubScopes;

    protected $cascadeDeletes = ['comments'];

    protected $fillable = [
        'creator_id',
        'recipients',
        'title',
        'description',
        'read_by_at',
    ];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
        'recipients' => 'array',
        'read_by_at' => 'array',
    ];

    protected $with = ['creator'];

    /**
     * Determine if the authenticated user is authorized to view this learning hub.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        $user = Auth::user();

        // If recipients is null, everyone is authorized to view
        if (is_null($this->recipients)) {
            return true;
        }

        // Ensure recipients is always an array
        $recipients = is_array($this->recipients) ? $this->recipients : json_decode($this->recipients, true);

        // Check if the user is the announcer or one of the recipients
        if ($this->creator_id == $user->id || in_array($user->id, $recipients)) {
            return true;
        }

        return false;
    }

    /**
     * Update the read_by_at array for a given user.
     */
    public function updateReadByAt(int $userId): void
    {
        $reads = $this->read_by_at;

        // Prevent duplicate entries
        $already = $reads->firstWhere('read_by.id', $userId) ?? $reads->firstWhere('read_by', $userId);

        if (!$already) {
            $reads->push([
                'read_by' => $userId,
                'read_at' => now()->toDateTimeString(),
            ]);

            $this->read_by_at = $reads; // Mutator handles JSON
            $this->save();
        }
    }
}
