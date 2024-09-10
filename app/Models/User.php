<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\User\Traits\Relations;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia, Relations, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = ['shortcuts', 'employee_shifts', 'attendances'];
    protected $dates = ['deleted_at'];

    protected $with = ['roles', 'media', 'shortcuts'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Prefix 'UID' to the 'userid' attribute
            $user->userid = 'UID' . $user->userid;
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(50)
                    ->height(50);
                $this->addMediaConversion('profile')
                    ->width(100)
                    ->height(100);
                $this->addMediaConversion('profile_view')
                    ->width(500)
                    ->height(500);
                $this->addMediaConversion('black_and_white')
                    ->greyscale()
                    ->quality(100);
                    // ->withResponsiveImages();
            });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->can($permission)) {
                return false;
            }
        }
        return true;
    }

    // Define an accessor to get the active_team_leader
    public function getActiveTeamLeaderAttribute()
    {
        // Return the first (and only) active team leader by $user->active_team_leader
        return $this->employee_team_leaders()->wherePivot('is_active', true)->first();
    }

    // Define an accessor to get the user_interactions
    public function getUserInteractionsAttribute()
    {
        // Get users this user has interacted with
        $interactedUsers = $this->interacted_users()->get();

        // Get users interacting with this user
        $interactingUsers = $this->interacting_users()->get();

        // Merge the two collections, remove duplicates if any and get by by $user->user_interactions
        return $interactedUsers->merge($interactingUsers)->unique('id');
    }

}
