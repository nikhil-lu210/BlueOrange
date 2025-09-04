<?php

namespace App\Models\FunctionalityWalkthrough;

use App\Traits\HasCustomRouteId;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\FunctionalityWalkthrough\Mutators\FunctionalityWalkthroughMutators;
use App\Models\FunctionalityWalkthrough\Accessors\FunctionalityWalkthroughAccessors;
use App\Models\FunctionalityWalkthrough\Relations\FunctionalityWalkthroughRelations;
use App\Models\FunctionalityWalkthrough\Scopes\FunctionalityWalkthroughScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\FunctionalityWalkthrough\FunctionalityWalkthroughObserver;

#[ObservedBy([FunctionalityWalkthroughObserver::class])]
class FunctionalityWalkthrough extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use FunctionalityWalkthroughRelations;

    // Accessors & Mutators
    use FunctionalityWalkthroughAccessors, FunctionalityWalkthroughMutators;

    // Scopes
    use FunctionalityWalkthroughScopes;

    protected $cascadeDeletes = ['steps', 'files'];

    protected $fillable = [
        'creator_id',
        'title',
        'assigned_roles',
        'read_by_at',
    ];

    protected $casts = [
        'assigned_roles' => 'array',
        'read_by_at' => 'array',
    ];

    protected $with = ['creator'];

    /**
     * Determine if the authenticated user is authorized to view this walkthrough.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        $user = Auth::user();

        // Check if user has "Functionality Walkthrough Everything" permission
        if ($user->hasPermissionTo('Functionality Walkthrough Everything')) {
            return true;
        }

        // Check if user is the creator
        if ($this->creator_id == $user->id) {
            return true;
        }

        // If assigned_roles is null, everyone is authorized to view
        if (is_null($this->assigned_roles)) {
            return true;
        }

        // Ensure assigned_roles is always an array
        $assignedRoles = is_array($this->assigned_roles) ? $this->assigned_roles : json_decode($this->assigned_roles, true);

        // Check if the user has any of the assigned roles
        $userRoleIds = $user->roles->pluck('id')->toArray();

        foreach ($assignedRoles as $roleId) {
            if (in_array($roleId, $userRoleIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update the read_by_at array for a given user.
     */
    public function updateReadByAt(int $userId): void
    {
        $reads = $this->read_by_at ?? collect();

        // Prevent duplicate entries
        $already = $reads->firstWhere('read_by', $userId);

        if (!$already) {
            $reads->push([
                'read_by' => $userId,
                'read_at' => now()->toDateTimeString(),
            ]);

            $this->read_by_at = $reads;
            $this->save();
        }
    }

}
