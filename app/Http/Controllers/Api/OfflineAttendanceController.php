<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance\Attendance;
use App\Models\Weekend\Weekend;
use App\Models\Holiday\Holiday;
use App\Http\Controllers\Controller;
use App\Services\Administration\Attendance\AttendanceEntryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OfflineAttendanceController extends Controller
{
    /**
     * Authorize user for sensitive operations (Sync from, Sync to, Clear All)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authorizeUser(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $email = $request->input('email');
            $password = $request->input('password');

            // Find user by email
            $user = User::where('email', $email)
                ->where('status', 'Active')
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive',
                    'data' => null
                ], 404);
            }

            // Verify password
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'data' => null
                ], 401);
            }

            // Check if user has "Attendance Create" permission
            $hasPermission = $user->hasPermissionTo('Attendance Create');

            if (!$hasPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this action',
                    'data' => null
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Authorization successful',
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'permissions' => ['Attendance Create']
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Authorization error', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authorization failed: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get user data by userid for offline sync
     *
     * @param string $userid
     * @return JsonResponse
     */
    public function getUserByUserid(string $userid): JsonResponse
    {
        try {
            $user = User::with('employee')
                ->where('userid', $userid)
                ->where('status', 'Active')
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive',
                    'data' => null
                ], 404);
            }

            $userData = [
                'id' => $user->id,
                'userid' => $user->userid,
                'name' => $user->name,
                'alias_name' => $user->employee?->alias_name ?? $user->name,
                'email' => $user->email
            ];

            return response()->json([
                'success' => true,
                'message' => 'User found',
                'data' => $userData
            ]);
        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error getting user', [
                'userid' => $userid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Check if user has open attendance on server
     *
     * @param string $userid
     * @return JsonResponse
     */
    public function checkUserAttendanceStatus(string $userid): JsonResponse
    {
        try {
            $user = User::where('userid', $userid)
                ->where('status', 'Active')
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive',
                    'data' => null
                ], 404);
            }

            // Check if user has open attendance on server
            $openAttendance = Attendance::where('user_id', $user->id)
                ->whereNull('clock_out')
                ->first();

            $hasOpenAttendance = !!$openAttendance;

            return response()->json([
                'success' => true,
                'message' => 'User attendance status retrieved',
                'data' => [
                    'user_id' => $user->id,
                    'userid' => $user->userid,
                    'has_open_attendance' => $hasOpenAttendance,
                    'open_attendance_id' => $openAttendance ? $openAttendance->id : null,
                    'clock_in_time' => $openAttendance ? $openAttendance->clock_in : null,
                    'clock_in_date' => $openAttendance ? $openAttendance->clock_in_date : null
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error checking user attendance status', [
                'userid' => $userid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error checking attendance status: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get all active users for offline sync
     *
     * @return JsonResponse
     */
    public function getAllUsers(): JsonResponse
    {
        try {
            $users = User::with('employee')
                ->where('status', 'Active')
                ->select('id', 'userid', 'name', 'email')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'userid' => $user->userid,
                        'name' => $user->name,
                        'alias_name' => $user->employee?->alias_name ?? $user->name,
                        'email' => $user->email
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users
            ]);
        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error getting all users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Sync offline attendance data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function syncAttendances(Request $request): JsonResponse
    {
        try {
            $attendances = $request->input('attendances', []);
            $syncedCount = 0;
            $errors = [];
            $syncedRecordIds = []; // Track which specific records were successfully synced

            $startTime = microtime(true);

            // Pre-load all users to avoid N+1 queries
            $userIds = collect($attendances)->pluck('user_id')->filter()->unique();
            $userids = collect($attendances)->pluck('userid')->filter()->unique();

            $users = collect();
            if ($userIds->isNotEmpty()) {
                $users = $users->merge(User::whereIn('id', $userIds)->get());
            }
            if ($userids->isNotEmpty()) {
                $users = $users->merge(User::whereIn('userid', $userids)->get());
            }

            $userLookup = $users->keyBy('id');
            $useridLookup = $users->keyBy('userid');

            foreach ($attendances as $index => $attendanceData) {
                try {
                    $originalIndex = $index; // Store the original index for tracking

                    // Validate required fields - support both old and new format
                    if (!isset($attendanceData['user_id']) && !isset($attendanceData['userid'])) {
                        $errors[] = 'Missing user_id or userid field for attendance record';
                        continue;
                    }

                    // Get user from pre-loaded collection
                    $user = null;
                    if (isset($attendanceData['user_id'])) {
                        $user = $userLookup->get($attendanceData['user_id']);
                    } else {
                        $user = $useridLookup->get($attendanceData['userid']);
                    }

                    if (!$user) {
                        $userId = $attendanceData['user_id'] ?? $attendanceData['userid'];
                        $errors[] = "User not found for user_id: {$userId}";
                        continue;
                    }

                    $type = $attendanceData['type'] ?? 'Regular';

                    // Handle new simplified format (entry records)
                    if (isset($attendanceData['entry_date_time'])) {
                        // Parse the entry datetime
                        $entryDateTime = Carbon::parse($attendanceData['entry_date_time']);

                        // Process this entry record
                        $attendance = $this->processEntryRecord($user, $type, $entryDateTime);

                        $syncedCount++;
                        $syncedRecordIds[] = $originalIndex; // Track this record as successfully synced
                    } else {
                        // Handle legacy format - convert to entry format
                        // Convert legacy format to entry format
                        $entryDateTime = null;
                        if (isset($attendanceData['clock_in_date']) && isset($attendanceData['clock_in'])) {
                            $clockInDate = Carbon::parse($attendanceData['clock_in_date']);
                            $clockInTime = Carbon::parse($attendanceData['clock_in']);
                            $entryDateTime = $clockInTime;
                        } elseif (isset($attendanceData['clock_out'])) {
                            $entryDateTime = Carbon::parse($attendanceData['clock_out']);
                        } elseif (isset($attendanceData['timestamp'])) {
                            $entryDateTime = Carbon::parse($attendanceData['timestamp']);
                        } else {
                            $errors[] = 'Invalid attendance data format - no recognizable datetime field';
                            continue;
                        }

                        $attendance = $this->processEntryRecord($user, $type, $entryDateTime);
                        $syncedCount++;
                        $syncedRecordIds[] = $originalIndex; // Track this record as successfully synced
                    }
                } catch (Exception $e) {
                    $userId = $attendanceData['user_id'] ?? $attendanceData['userid'] ?? 'unknown';
                    $errorMsg = "Error processing attendance for {$userId}: " . $e->getMessage();
                    $errors[] = $errorMsg;
                    Log::error('Offline Attendance API: Error processing attendance', [
                        'userid' => $userId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            $endTime = microtime(true);

            // Only return success if all records were synced successfully
            $allSynced = $syncedCount === count($attendances) && count($errors) === 0;

            return response()->json([
                'success' => $allSynced,
                'message' => $allSynced
                    ? "Successfully synced {$syncedCount} attendance records"
                    : "Synced {$syncedCount} of " . count($attendances) . " attendance records. " . count($errors) . " errors occurred.",
                'data' => [
                    'synced_count' => $syncedCount,
                    'total_count' => count($attendances),
                    'synced_record_ids' => $syncedRecordIds, // Array of original indices that were successfully synced
                    'errors' => $errors
                ]
            ], $allSynced ? 200 : 422);
        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error syncing attendances', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error syncing attendances: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Process an entry record - smart logic to determine clock-in vs clock-out
     *
     * @param User $user
     * @param string $type
     * @param Carbon $entryDateTime
     * @return Attendance
     * @throws Exception
     */
    private function processEntryRecord($user, $type, $entryDateTime)
    {
        // Get the active employee shift
        $activeShift = $user->employee_shifts()
            ->where('status', 'Active')
            ->latest('created_at')
            ->first();

        if (!$activeShift) {
            throw new Exception('No active shift found for this user.');
        }

        // Check if user has an open attendance (clocked in but not clocked out)
        $openAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        if ($openAttendance) {
            // User has open attendance - this entry should be a clock-out
            return $this->processClockOut($openAttendance, $entryDateTime, $activeShift);
        } else {
            // User has no open attendance - this entry should be a clock-in
            return $this->processClockIn($user, $type, $entryDateTime, $activeShift);
        }
    }

    /**
     * Process a clock-in entry
     */
    private function processClockIn($user, $type, $entryDateTime, $activeShift)
    {
        $entryDate = $entryDateTime->toDateString();

        // Validate business rules
        $isWeekend = Weekend::where('day', $entryDateTime->format('l'))->where('is_active', true)->exists();
        if ($isWeekend && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Weekend. Please clock in as Overtime.');
        }

        $isHoliday = Holiday::where('date', $entryDate)->where('is_active', true)->exists();
        if ($isHoliday && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Holiday. Please clock in as Overtime.');
        }

        // Check if user already clocked in as Regular today
        $existingRegularAttendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $entryDate)
            ->where('type', 'Regular')
            ->whereNull('clock_out') // Only check for open attendances
            ->first();

        if ($existingRegularAttendance && $type === 'Regular') {
            // If there's already an open Regular attendance, this entry should be treated as clock-out
            return $this->processClockOut($existingRegularAttendance, $entryDateTime, $activeShift);
        }

        // Create clock-in record
        return Attendance::create([
            'user_id' => $user->id,
            'employee_shift_id' => $activeShift->id,
            'clock_in_date' => $entryDate,
            'clock_in' => $entryDateTime,
            'type' => $type,
            'clockin_medium' => 'Barcode',
            'ip_address' => request()->ip(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Process a clock-out entry
     */
    private function processClockOut($openAttendance, $entryDateTime, $activeShift)
    {
        // Check minimum time between clock-in and clock-out (2 minutes)
        $minClockOutTime = $openAttendance->clock_in->copy()->addMinutes(2);
        if ($entryDateTime < $minClockOutTime) {
            // For offline entries, adjust the clock-out time to meet minimum requirement
            // This handles cases where users make rapid entries in offline mode
            $entryDateTime = $minClockOutTime;
        }

        // Calculate total time
        $totalSeconds = $entryDateTime->diffInSeconds($openAttendance->clock_in);
        $formattedTotalTime = gmdate('H:i:s', $totalSeconds);

        // Calculate adjusted time based on shift
        $formattedAdjustedTotalTime = $formattedTotalTime;
        if ($openAttendance->type === 'Regular' && $activeShift) {
            list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $activeShift->total_time);
            $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;
            $adjustedTotalSeconds = min($totalSeconds, $shiftTotalSeconds);
            $formattedAdjustedTotalTime = gmdate('H:i:s', $adjustedTotalSeconds);
        }

        // Update the open attendance with clock-out
        $openAttendance->update([
            'clock_out' => $entryDateTime,
            'total_time' => $formattedTotalTime,
            'total_adjusted_time' => $formattedAdjustedTotalTime,
            'clockout_medium' => 'Barcode',
            'updated_at' => now()
        ]);

        return $openAttendance;
    }
}
