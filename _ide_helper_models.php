<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Attendance{
/**
 * App\Models\Attendance\Attendance
 *
 * @property int $id
 * @property int $user_id
 * @property string $clock_in_date
 * @property \Illuminate\Support\Carbon $clock_in
 * @property \Illuminate\Support\Carbon|null $clock_out
 * @property string|null $total_time
 * @property string|null $ip_address
 * @property string|null $country
 * @property string|null $city
 * @property string|null $zip_code
 * @property string|null $time_zone
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTotalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance withoutTrashed()
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PermissionModule
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionModule withoutTrashed()
 */
	class PermissionModule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $avatar
 * @property string $userid
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkingShift\Shift> $shifts
 * @property-read int|null $shifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\WorkingShift{
/**
 * App\Models\WorkingShift\Shift
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift withoutTrashed()
 */
	class Shift extends \Eloquent {}
}

