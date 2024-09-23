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
 * @property string $title
 * @property string|array $description
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
 * @property string|array $comment
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
 * @property string $type
 * @property int|null $qr_clockin_scanner_id
 * @property int|null $qr_clockout_scanner_id
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
 * @property-read \App\Models\User|null $qr_clockin_scanner
 * @property-read \App\Models\User|null $qr_clockout_scanner
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\Attendance\AttendanceFactory factory($count = null, $state = [])
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
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereQrClockinScannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereQrClockoutScannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTotalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance withoutTrashed()
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models\Chatting{
/**
 * App\Models\Chatting\Chatting
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string|array|null $message
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Chatting withoutTrashed()
 */
	class Chatting extends \Eloquent {}
}

namespace App\Models\Chatting{
/**
 * App\Models\Chatting\ChattingGroup
 *
 * @property int $id
 * @property string $groupid
 * @property string $name
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chatting\GroupChatting> $group_messages
 * @property-read int|null $group_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $group_users
 * @property-read int|null $group_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereGroupid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChattingGroup withoutTrashed()
 */
	class ChattingGroup extends \Eloquent {}
}

namespace App\Models\Chatting{
/**
 * App\Models\Chatting\GroupChatting
 *
 * @property int $id
 * @property int $chatting_group_id
 * @property int $sender_id
 * @property string|array|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Chatting\ChattingGroup $group
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereChattingGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupChatting withoutTrashed()
 */
	class GroupChatting extends \Eloquent {}
}

namespace App\Models\DailyWorkUpdate{
/**
 * App\Models\DailyWorkUpdate\DailyWorkUpdate
 *
 * @property int $id
 * @property int $user_id
 * @property int $team_leader_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|array $work_update Daily Work Update Here.
 * @property int $progress
 * @property string|array|null $note Client Respond / Any Issue Note Here.
 * @property int|null $rating
 * @property string|null $comment Team Leader Comment Here.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\User $team_leader
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereTeamLeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate whereWorkUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyWorkUpdate withoutTrashed()
 */
	class DailyWorkUpdate extends \Eloquent {}
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

namespace App\Models\FileMedia{
/**
 * App\Models\FileMedia\FileMedia
 *
 * @property int $id
 * @property int $uploader_id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $file_name
 * @property string $file_path
 * @property string $mime_type
 * @property int $file_size
 * @property string $original_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $fileable
 * @property-read \App\Models\User $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia whereUploaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FileMedia withoutTrashed()
 */
	class FileMedia extends \Eloquent {}
}

namespace App\Models\Holiday{
/**
 * App\Models\Holiday\Holiday
 *
 * @property int $id
 * @property string $date
 * @property string $name
 * @property string|array|null $description
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

namespace App\Models\Shortcut{
/**
 * App\Models\Shortcut\Shortcut
 *
 * @property int $id
 * @property int $user_id
 * @property string $icon
 * @property string $name
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shortcut withoutTrashed()
 */
	class Shortcut extends \Eloquent {}
}

namespace App\Models\Task{
/**
 * App\Models\Task\Task
 *
 * @property int $id
 * @property string $taskid
 * @property int $creator_id
 * @property string $title
 * @property string|array $description
 * @property string|null $deadline
 * @property string $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task\TaskComment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task\TaskHistory> $histories
 * @property-read int|null $histories_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTaskid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTrashed()
 */
	class Task extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Task{
/**
 * App\Models\Task\TaskComment
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string|array $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $commenter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Task\Task $task
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskComment withoutTrashed()
 */
	class TaskComment extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\Task{
/**
 * App\Models\Task\TaskHistory
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property string|null $total_worked
 * @property string|array|null $note
 * @property int $progress
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Task\Task $task
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereTotalWorked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskHistory withoutTrashed()
 */
	class TaskHistory extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $userid
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $status
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chatting\ChattingGroup> $chatting_groups
 * @property-read int|null $chatting_groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task\Task> $created_tasks
 * @property-read int|null $created_tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyWorkUpdate\DailyWorkUpdate> $daily_work_updates
 * @property-read int|null $daily_work_updates_count
 * @property-read \App\Models\User\Employee\Employee|null $employee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeShift\EmployeeShift> $employee_shifts
 * @property-read int|null $employee_shifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $employee_team_leaders
 * @property-read int|null $employee_team_leaders_count
 * @property-read mixed $active_team_leader
 * @property-read mixed $current_salary
 * @property-read mixed $current_shift
 * @property-read mixed $user_interactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $interacted_users
 * @property-read int|null $interacted_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $interacting_users
 * @property-read int|null $interacting_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\LoginHistory> $login_logout_histories
 * @property-read int|null $login_logout_histories_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalary> $monthly_salaries
 * @property-read int|null $monthly_salaries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $qr_clockins
 * @property-read int|null $qr_clockins_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $qr_clockouts
 * @property-read int|null $qr_clockouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Salary> $salaries
 * @property-read int|null $salaries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shortcut\Shortcut> $shortcuts
 * @property-read int|null $shortcuts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task\TaskComment> $task_comments
 * @property-read int|null $task_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $tl_employees
 * @property-read int|null $tl_employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyWorkUpdate\DailyWorkUpdate> $tl_employees_daily_work_updates
 * @property-read int|null $tl_employees_daily_work_updates_count
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
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models\User\Employee{
/**
 * App\Models\User\Employee\Employee
 *
 * @property int $id
 * @property int $user_id
 * @property string $joining_date
 * @property string|null $alias_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string|null $birth_date
 * @property string|null $personal_email
 * @property string|null $official_email
 * @property string|null $personal_contact_no
 * @property string|null $official_contact_no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAliasName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereMotherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereOfficialContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereOfficialEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePersonalContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePersonalEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withoutTrashed()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\LoginHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $login_time
 * @property string|null $logout_time
 * @property string $login_ip
 * @property string|null $logout_ip
 * @property string $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLogoutIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLogoutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory withoutTrashed()
 */
	class LoginHistory extends \Eloquent {}
}

