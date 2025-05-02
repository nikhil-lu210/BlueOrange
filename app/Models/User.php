<?php

namespace App\Models;

use App\Traits\HasCustomRouteId;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\User\Mutators\UserMutators;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\User\Accessors\UserAccessors;
use App\Models\User\Relations\UserRelations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, Authorizable, HasRoles, InteractsWithMedia, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations
    use UserRelations;

    // Accessors & Mutators
    use UserAccessors, UserMutators;

    protected $cascadeDeletes = [
        // 'employee',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // We're removing the automatic loading of relationships to prevent n+1 queries
    // Instead, we'll explicitly load what we need in each controller
    protected $with = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'first_name',
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


    /**
     * Boot the model and define event hooks.
     *
     * Automatically modifies attributes or performs operations during model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Hook into the creating event to modify the 'userid' attribute
        static::creating(function ($user) {
            // Prefix 'UID' to the 'userid' attribute
            $user->userid = 'UID' . $user->userid;
        });
    }

    /**
     * Register media collections for this model.
     *
     * Defines the 'avatar' media collection and associated media conversions.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile() // Restricts the collection to hold a single file
            ->acceptsMimeTypes(['image/jpeg', 'image/png']) // Restricts acceptable file types
            ->registerMediaConversions(function (Media $media) {
                // Define media conversions for various use cases

                // Thumbnail black and white conversion (50x50 pixels)
                $this->addMediaConversion('thumb')
                    ->greyscale()
                    ->quality(100)
                    ->width(50)
                    ->height(50);

                // Thumbnail conversion (50x50 pixels)
                $this->addMediaConversion('thumb_color')
                    ->width(50)
                    ->height(50);

                // Profile-sized black & white image conversion (100x100 pixels)
                $this->addMediaConversion('profile')
                    ->greyscale()
                    ->quality(100)
                    ->width(100)
                    ->height(100);

                // Profile-sized image conversion (100x100 pixels)
                $this->addMediaConversion('profile_color')
                    ->width(100)
                    ->height(100);

                // Larger profile black_and_white view conversion (500x500 pixels)
                $this->addMediaConversion('profile_view')
                    ->greyscale()
                    ->quality(100)
                    ->width(500)
                    ->height(500);

                // Larger profile view conversion (500x500 pixels)
                $this->addMediaConversion('profile_view_color')
                    ->quality(100)
                    ->width(500)
                    ->height(500);

                // Black and white conversion with 100% quality
                $this->addMediaConversion('black_and_white')
                    ->greyscale()
                    ->quality(100);
                    // Uncomment the following line to include responsive images
                    // ->withResponsiveImages();
            });
    }

    /**
     * Check if the user has all specified permissions.
     *
     * Iterates over an array of permissions and verifies if the user has all of them.
     *
     * @param array $permissions List of permission names to check.
     * @return bool True if the user has all the specified permissions; otherwise, false.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            // If any permission check fails, return false
            if (!$this->can($permission)) {
                return false;
            }
        }

        // Return true if all permissions are granted
        return true;
    }
}
