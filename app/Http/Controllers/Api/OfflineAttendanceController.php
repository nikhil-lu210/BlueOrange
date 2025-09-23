<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Services\Api\OfflineAttendance\OfflineAttendanceService;
use App\Http\Requests\Api\OfflineAttendance\OfflineAttendanceAuthorizeRequest;
use App\Http\Requests\Api\OfflineAttendance\OfflineAttendanceSyncRequest;
use App\Http\Resources\Api\OfflineAttendance\UserResource;
use App\Http\Resources\Api\OfflineAttendance\AuthorizationResource;
use App\Http\Resources\Api\OfflineAttendance\AttendanceStatusResource;
use App\Http\Resources\Api\OfflineAttendance\SyncResultResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OfflineAttendanceController extends Controller
{
    use ApiResponseTrait;

    protected OfflineAttendanceService $offlineAttendanceService;

    public function __construct(OfflineAttendanceService $offlineAttendanceService)
    {
        $this->offlineAttendanceService = $offlineAttendanceService;
    }

    /**
     * Authorize user for sensitive operations (Sync from, Sync to, Clear All)
     */
    public function authorizeUser(OfflineAttendanceAuthorizeRequest $request): JsonResponse
    {
        try {
            $authorizationData = $this->offlineAttendanceService->authorizeUser(
                $request->input('email'),
                $request->input('password')
            );

            return $this->resourceResponse(
                new AuthorizationResource($authorizationData),
                'Authorization successful'
            );

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Authorization error', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $statusCode = match (true) {
                str_contains($e->getMessage(), 'not found') => 404,
                str_contains($e->getMessage(), 'Invalid credentials') => 401,
                str_contains($e->getMessage(), 'permission') => 403,
                default => 500
            };

            return $this->errorResponse(
                'Authorization failed: ' . $e->getMessage(),
                null,
                $statusCode
            );
        }
    }

    /**
     * Get user data by userid for offline sync
     */
    public function getUserByUserid(string $userid): JsonResponse
    {
        try {
            $userData = $this->offlineAttendanceService->getUserByUserid($userid);

            return $this->resourceResponse(
                new UserResource($userData),
                'User found'
            );

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error getting user', [
                'userid' => $userid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse(
                'Error retrieving user: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Check if user has open attendance on server
     */
    public function checkUserAttendanceStatus(string $userid): JsonResponse
    {
        try {
            $statusData = $this->offlineAttendanceService->checkUserAttendanceStatus($userid);

            return $this->resourceResponse(
                new AttendanceStatusResource($statusData),
                'User attendance status retrieved'
            );

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error checking user attendance status', [
                'userid' => $userid,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse(
                'Error checking attendance status: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Get all active users for offline sync
     */
    public function getAllUsers(): JsonResponse
    {
        try {
            $users = $this->offlineAttendanceService->getAllUsers();

            return $this->collectionResponse(
                UserResource::collection($users),
                'Users retrieved successfully'
            );

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error getting all users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse(
                'Error retrieving users: ' . $e->getMessage(),
                [],
                500
            );
        }
    }

    /**
     * Sync offline attendance data
     */
    public function syncAttendances(OfflineAttendanceSyncRequest $request): JsonResponse
    {
        try {
            $result = $this->offlineAttendanceService->syncAttendances(
                $request->input('attendances', [])
            );

            $message = $result['success']
                ? "Successfully synced {$result['synced_count']} attendance records"
                : "Synced {$result['synced_count']} of {$result['total_count']} attendance records. " . count($result['errors']) . " errors occurred.";

            return $this->resourceResponse(
                new SyncResultResource($result),
                $message,
                $result['success'] ? 200 : 422
            );

        } catch (Exception $e) {
            Log::error('Offline Attendance API: Error syncing attendances', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse(
                'Error syncing attendances: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

}
