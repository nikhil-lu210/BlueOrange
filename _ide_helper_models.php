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
 * @property string|null $total_time hh:mm:ss format to be store
 * @property string|null $total_adjusted_time hh:mm:ss format to be store
 * @property string $type
 * @property string $clockin_medium
 * @property string|null $clockout_medium
 * @property int|null $clockin_scanner_id
 * @property int|null $clockout_scanner_id
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
 * @property-read \App\Models\User|null $clockin_scanner
 * @property-read \App\Models\User|null $clockout_scanner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyBreak\DailyBreak> $daily_breaks
 * @property-read int|null $daily_breaks_count
 * @property-read \App\Models\EmployeeShift\EmployeeShift $employee_shift
 * @property-read mixed $total_break_time
 * @property-read int $total_breaks_taken
 * @property-read mixed $total_over_break
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
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockinMedium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockinScannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockoutMedium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereClockoutScannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereEmployeeShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTotalAdjustedTime($value)
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
 * @property-read \App\Models\Task\Task|null $task
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

namespace App\Models\DailyBreak{
/**
 * App\Models\DailyBreak\DailyBreak
 *
 * @property int $id
 * @property int $user_id
 * @property int $attendance_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon $break_in_at
 * @property \Illuminate\Support\Carbon|null $break_out_at
 * @property string|null $total_time hh:mm:ss
 * @property string|null $over_break hh:mm:ss
 * @property string $type
 * @property string $break_in_ip
 * @property string|null $break_out_ip
 * @property string|array|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Attendance\Attendance $attendance
 * @property-write mixed $clock_in
 * @property-write mixed $clock_out
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DailyBreak\DailyBreakFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereAttendanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereBreakInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereBreakInIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereBreakOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereBreakOutIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereOverBreak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereTotalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBreak withoutTrashed()
 */
	class DailyBreak extends \Eloquent {}
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
 * @property string $total_time hh:mm:ss format to be store
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
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeShift whereTotalTime($value)
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

namespace App\Models\IncomeExpense{
/**
 * App\Models\IncomeExpense\Expense
 *
 * @property int $id
 * @property int $creator_id
 * @property int $category_id
 * @property string $title
 * @property \Illuminate\Support\Carbon $date
 * @property int $quantity
 * @property float $price
 * @property float $total
 * @property string|array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\IncomeExpense\IncomeExpenseCategory $category
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @method static \Database\Factories\IncomeExpense\ExpenseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense withoutTrashed()
 */
	class Expense extends \Eloquent {}
}

namespace App\Models\IncomeExpense{
/**
 * App\Models\IncomeExpense\Income
 *
 * @property int $id
 * @property int $creator_id
 * @property int $category_id
 * @property string $source
 * @property \Illuminate\Support\Carbon $date
 * @property float $total
 * @property string|array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\IncomeExpense\IncomeExpenseCategory $category
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @method static \Database\Factories\IncomeExpense\IncomeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Income newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Income newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Income onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Income query()
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Income withoutTrashed()
 */
	class Income extends \Eloquent {}
}

namespace App\Models\IncomeExpense{
/**
 * App\Models\IncomeExpense\IncomeExpenseCategory
 *
 * @property int $id
 * @property string $name
 * @property string|array|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IncomeExpense\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IncomeExpense\Income> $incomes
 * @property-read int|null $incomes_count
 * @method static \Database\Factories\IncomeExpense\IncomeExpenseCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IncomeExpenseCategory withoutTrashed()
 */
	class IncomeExpenseCategory extends \Eloquent {}
}

namespace App\Models\Leave{
/**
 * App\Models\Leave\LeaveAllowed
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\CarbonInterval $earned_leave Store as hh:mm:ss format
 * @property \Carbon\CarbonInterval $casual_leave Store as hh:mm:ss format
 * @property \Carbon\CarbonInterval $sick_leave Store as hh:mm:ss format
 * @property \App\Models\Leave\Carbon $implemented_from Store as mm-dd format
 * @property \App\Models\Leave\Carbon $implemented_to Store as mm-dd format
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Leave\LeaveHistory> $leave_histories
 * @property-read int|null $leave_histories_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereCasualLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereEarnedLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereImplementedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereImplementedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereSickLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAllowed withoutTrashed()
 */
	class LeaveAllowed extends \Eloquent {}
}

namespace App\Models\Leave{
/**
 * App\Models\Leave\LeaveAvailable
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $for_year
 * @property \Carbon\CarbonInterval $earned_leave Store as hh:mm:ss format
 * @property \Carbon\CarbonInterval $casual_leave Store as hh:mm:ss format
 * @property \Carbon\CarbonInterval $sick_leave Store as hh:mm:ss format
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereCasualLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereEarnedLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereForYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereSickLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveAvailable withoutTrashed()
 */
	class LeaveAvailable extends \Eloquent {}
}

namespace App\Models\Leave{
/**
 * App\Models\Leave\LeaveHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $leave_allowed_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Carbon\CarbonInterval $total_leave Store as hh:mm:ss format
 * @property string $type
 * @property bool|null $is_paid_leave
 * @property string $status
 * @property string $reason
 * @property int|null $reviewed_by
 * @property string|null $reviewed_at
 * @property string $reviewer_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Leave\LeaveAllowed $leave_allowed
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereIsPaidLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereLeaveAllowedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereReviewerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereTotalLeave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveHistory withoutTrashed()
 */
	class LeaveHistory extends \Eloquent {}
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
 * @property string $payslip_id
 * @property int $user_id
 * @property int $salary_id
 * @property string $for_month previous month in Y-m format
 * @property int $total_workable_days
 * @property int $total_weekends
 * @property int|null $total_holidays
 * @property float $hourly_rate
 * @property float $total_payable
 * @property int|null $paid_by
 * @property string|null $paid_through Paid Through Cash / Bank Transfer / Cheque Book / Mobile Banking (bkash, Nagad, uPay etc.)
 * @property string|null $payment_proof
 * @property string|null $paid_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileMedia\FileMedia> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalaryBreakdown> $monthly_salary_breakdowns
 * @property-read int|null $monthly_salary_breakdowns_count
 * @property-read \App\Models\User|null $payer
 * @property-read \App\Models\Salary\Salary $salary
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereForMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereHourlyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary wherePaidThrough($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary wherePaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary wherePayslipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereSalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereTotalHolidays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereTotalPayable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereTotalWeekends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereTotalWorkableDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalary withoutTrashed()
 */
	class MonthlySalary extends \Eloquent {}
}

namespace App\Models\Salary\Monthly{
/**
 * App\Models\Salary\Monthly\MonthlySalaryBreakdown
 *
 * @property int $id
 * @property int $monthly_salary_id
 * @property string $type
 * @property string $reason
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Salary\Monthly\MonthlySalary $monthly_salary
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereMonthlySalaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlySalaryBreakdown withoutTrashed()
 */
	class MonthlySalaryBreakdown extends \Eloquent {}
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
 * @property float $total
 * @property string $implemented_from
 * @property string|null $implemented_to
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

namespace App\Models\Settings{
/**
 * App\Models\Settings\Settings
 *
 * @property int $id
 * @property string $key
 * @property mixed $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereValue($value)
 */
	class Settings extends \Eloquent {}
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
 * @property int|null $chatting_id
 * @property int $creator_id
 * @property string $title
 * @property string|array $description
 * @property \Illuminate\Support\Carbon|null $deadline
 * @property string $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Chatting\Chatting|null $chatting
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
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereChattingId($value)
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

namespace App\Models\Ticket{
/**
 * App\Models\Ticket\ItTicket
 *
 * @property int $id
 * @property int $creator_id
 * @property string $title
 * @property string|array $description
 * @property array $seen_by
 * @property int|null $solved_by
 * @property \Illuminate\Support\Carbon|null $solved_at
 * @property string $status
 * @property string|array|null $solver_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User|null $solver
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereSeenBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereSolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereSolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereSolverNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ItTicket withoutTrashed()
 */
	class ItTicket extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyBreak\DailyBreak> $daily_breaks
 * @property-read int|null $daily_breaks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyWorkUpdate\DailyWorkUpdate> $daily_work_updates
 * @property-read int|null $daily_work_updates_count
 * @property-read \App\Models\User\Employee\Employee|null $employee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeShift\EmployeeShift> $employee_shifts
 * @property-read int|null $employee_shifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $employee_team_leaders
 * @property-read int|null $employee_team_leaders_count
 * @property-read \App\Models\User|null $active_team_leader
 * @property-read \App\Models\LeaveAllowed|null $allowed_leave
 * @property-read \App\Models\Salary|null $current_salary
 * @property-read \App\Models\EmployeeShift|null $current_shift
 * @property-read \Spatie\Permission\Models\Role|null $role
 * @property-read \App\Models\Collection $user_interactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $interacted_users
 * @property-read int|null $interacted_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $interacting_users
 * @property-read int|null $interacting_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket\ItTicket> $it_ticket_solves
 * @property-read int|null $it_ticket_solves_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket\ItTicket> $it_tickets
 * @property-read int|null $it_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Leave\LeaveAllowed> $leave_alloweds
 * @property-read int|null $leave_alloweds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Leave\LeaveAvailable> $leave_availables
 * @property-read int|null $leave_availables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Leave\LeaveHistory> $leave_histories
 * @property-read int|null $leave_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\LoginHistory> $login_logout_histories
 * @property-read int|null $login_logout_histories_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalary> $monthly_salaries
 * @property-read int|null $monthly_salaries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Monthly\MonthlySalary> $paid_salaries
 * @property-read int|null $paid_salaries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary\Salary> $salaries
 * @property-read int|null $salaries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $scanner_clockins
 * @property-read int|null $scanner_clockins_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance\Attendance> $scanner_clockouts
 * @property-read int|null $scanner_clockouts_count
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vault\Vault> $vaults
 * @property-read int|null $vaults_count
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

namespace App\Models\Vault{
/**
 * App\Models\Vault\Vault
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $url
 * @property string $username
 * @property string $password
 * @property string|array|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $viewers
 * @property-read int|null $viewers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Vault newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault withoutTrashed()
 */
	class Vault extends \Eloquent {}
}

namespace App\Models\Weekend{
/**
 * App\Models\Weekend\Weekend
 *
 * @property int $id
 * @property string $day
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend query()
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Weekend withoutTrashed()
 */
	class Weekend extends \Eloquent {}
}

