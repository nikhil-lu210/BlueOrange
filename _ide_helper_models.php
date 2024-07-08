<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Announcement{
/**
 * App\Models\Announcement\Announcement
 *
 * @property int $id
 * @property int $announcer_id
 * @property string $description
 * @property string $title
 * @property array|null $recipients JSON field to hold user IDs for recipients
 * @property array|null $read_by_at JSON field to track read status by user
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $announcer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement\AnnouncementComment> $comments
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereAnnouncerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereReadByAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement withoutTrashed()
 */
	class Announcement extends \Eloquent {}
}

namespace App\Models\Announcement{
/**
 * App\Models\Announcement\AnnouncementComment
 *
 * @property int $id
 * @property int $announcement_id
 * @property int $commenter_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Announcement\Announcement $announcement
 * @property-read \App\Models\User $commenter
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereAnnouncementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereCommenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementComment withoutTrashed()
 */
	class AnnouncementComment extends \Eloquent {}
}

namespace App\Models\Attendance{
/**
 * App\Models\Attendance\Attendance
 *
 * @property int $id
 * @property int $user_id
 * @property int $employee_shift_id
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
 * @property-read \App\Models\EmployeeShift\EmployeeShift $employee_shift
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
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereEmployeeShiftId($value)
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

namespace App\Models\EmployeeShift{
/**
 * App\Models\EmployeeShift\EmployeeShift
 *
 * @property int $id
 * @property int $user_id
 * @property string $start_time
 * @property string $end_time
 * @property string $implemented_from
 * @property string|null $implemented_to
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereImplementedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereImplementedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift withoutTrashed()
 */
	class EmployeeShift extends \Eloquent {}
}

namespace App\Models\Holiday{
/**
 * App\Models\Holiday\Holiday
 *
 * @property int $id
 * @property string $date
 * @property string $name
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday query()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Holiday withoutTrashed()
 */
	class Holiday extends \Eloquent {}
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

namespace App\Models\Salary\Monthly{
/**
 * App\Models\Salary\Monthly\MonthlySalary
 *
 * @property int $id
 * @property int $user_id
 * @property int $salary_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Salary\Salary $salary
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereSalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary withoutTrashed()
 */
	class MonthlySalary extends \Eloquent {}
}

namespace App\Models\Salary{
/**
 * App\Models\Salary\Salary
 *
 * @property int $id
 * @property int $user_id
 * @property float $basic_salary
 * @property float $house_benefit
 * @property float $transport_allowance
 * @property float $medical_allowance
 * @property float|null $night_shift_allowance
 * @property float|null $other_allowance
 * @property string $implemented_from
 * @property string|null $implemented_to
 * @property float $total
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalary> $monthly_salaries
 * @property-read int|null $monthly_salaries_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Salary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereHouseBenefit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereImplementedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereImplementedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereMedicalAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereNightShiftAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereOtherAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereTransportAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Salary withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary withoutTrashed()
 */
	class Salary extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement\AnnouncementComment> $announcement_comments
 * @property-read int|null $announcement_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement\Announcement> $announcements
 * @property-read int|null $announcements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeShift\EmployeeShift> $employee_shifts
 * @property-read int|null $employee_shifts_count
 * @property-read mixed $current_salary
 * @property-read mixed $current_shift
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalary> $monthly_salaries
 * @property-read int|null $monthly_salaries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Salary> $salaries
 * @property-read int|null $salaries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
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

