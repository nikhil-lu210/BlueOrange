# Offline Attendance API Documentation

## Overview

The Offline Attendance API provides endpoints for managing attendance data in the BO_React offline-first Progressive Web Application (PWA). This API supports user lookup, attendance status checking, authorization, and synchronization of offline attendance records with the BlueOrange Laravel backend.

## Architecture

The BO_React application uses a hybrid architecture:
- **Frontend**: React 19 + TypeScript + Vite
- **State Management**: Zustand stores for reactive UI updates
- **Storage**: localStorage for offline data persistence
- **Backend**: Laravel API with Spatie/Permission integration
- **Authentication**: Email/password validation with role-based access control

## Base URL

```
http://blueorange.test/api
```

## Authentication

Most endpoints require proper authentication through the authorization endpoint. The BO_React application uses a two-step authentication process:

1. **Authorization Request**: User provides email/password
2. **Permission Validation**: Server validates user has "Attendance Create" permission
3. **Active Status Check**: Ensures user account is active

### Authorization Endpoint

**Endpoint:** `POST /offline-attendance/authorize`

**Request Body:**
```json
{
  "email": "user@company.com",
  "password": "userpassword"
}
```

**Response:**

**Success (200):**
```json
{
  "success": true,
  "message": "Authorization successful",
  "data": {
    "user": {
      "id": 123,
      "name": "John Doe",
      "email": "john@company.com",
      "has_permission": true,
      "is_active": true
    }
  }
}
```

**Error (401):**
```json
{
  "success": false,
  "message": "Invalid credentials",
  "data": null
}
```

**Error (403):**
```json
{
  "success": false,
  "message": "User does not have Attendance Create permission",
  "data": null
}
```

## Endpoints

### 1. Get User by User ID

Retrieves user information by their user ID for offline sync.

**Endpoint:** `GET /offline-attendance/user/{userid}`

**Parameters:**
- `userid` (string, required) - The user ID to lookup

**Response:**

**Success (200):**
```json
{
  "success": true,
  "message": "User found",
  "data": {
    "id": 123,
    "userid": "EMP001",
    "name": "John Doe",
    "alias_name": "John",
    "email": "john.doe@company.com"
  }
}
```

**Error (404):**
```json
{
  "success": false,
  "message": "User not found or inactive",
  "data": null
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "Error retrieving user: [error message]",
  "data": null
}
```

### 2. Check User Attendance Status

Checks if a user has an open attendance record on the server.

**Endpoint:** `GET /offline-attendance/user/{userid}/status`

**Parameters:**
- `userid` (string, required) - The user ID to check

**Response:**

**Success (200):**
```json
{
  "success": true,
  "message": "User attendance status retrieved",
  "data": {
    "user_id": 123,
    "userid": "EMP001",
    "has_open_attendance": true,
    "open_attendance_id": 456,
    "clock_in_time": "2024-01-15T09:00:00.000000Z",
    "clock_in_date": "2024-01-15"
  }
}
```

**Error (404):**
```json
{
  "success": false,
  "message": "User not found or inactive",
  "data": null
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "Error checking attendance status: [error message]",
  "data": null
}
```

### 3. Get All Active Users

Retrieves all active users for offline synchronization.

**Endpoint:** `GET /offline-attendance/users`

**Response:**

**Success (200):**
```json
{
  "success": true,
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": 123,
      "userid": "EMP001",
      "name": "John Doe",
      "alias_name": "John",
      "email": "john.doe@company.com"
    },
    {
      "id": 124,
      "userid": "EMP002",
      "name": "Jane Smith",
      "alias_name": "Jane",
      "email": "jane.smith@company.com"
    }
  ]
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "Error retrieving users: [error message]",
  "data": []
}
```

### 4. Sync Offline Attendances

Synchronizes offline attendance records to the main server. This endpoint processes attendance entries and intelligently determines whether each entry should be treated as a clock-in or clock-out based on the user's current attendance status.

**Endpoint:** `POST /offline-attendance/sync`

**Request Body:**
```json
{
  "attendances": [
    {
      "user_id": 123,
      "entry_date_time": "2024-01-15T09:00:00.000000Z",
      "type": "Regular"
    },
    {
      "user_id": 124,
      "entry_date_time": "2024-01-15T17:30:00.000000Z",
      "type": "Overtime"
    }
  ]
}
```

**Request Fields:**
- `attendances` (array, required) - Array of attendance records
  - `user_id` (integer, required) - User ID (alternative to userid)
  - `userid` (string, optional) - User ID string (alternative to user_id)
  - `entry_date_time` (string, required) - ISO 8601 datetime when the entry was made
  - `type` (string, optional) - Attendance type: "Regular" or "Overtime" (defaults to "Regular")

**Legacy Format Support:**
The API also supports legacy format for backward compatibility:
```json
{
  "attendances": [
    {
      "user_id": 123,
      "clock_in_date": "2024-01-15",
      "clock_in": "09:00:00",
      "clock_out": "17:30:00",
      "type": "Regular"
    }
  ]
}
```

**Response:**

**Success (200):**
```json
{
  "success": true,
  "message": "Successfully synced 2 attendance records",
  "data": {
    "synced_count": 2,
    "total_count": 2,
    "errors": []
  }
}
```

**Partial Success (422):**
```json
{
  "success": false,
  "message": "Synced 1 of 2 attendance records. 1 errors occurred.",
  "data": {
    "synced_count": 1,
    "total_count": 2,
    "synced_record_ids": [0],
    "errors": [
      "Error processing attendance for 124: You cannot Regular Clock-In on Weekend. Please clock in as Overtime."
    ]
  }
}
```

**Note**: The `synced_record_ids` array contains the indices of successfully processed records from the original request array. This allows the BO_React application to mark only the successfully synced records as synced in local storage.

**Error (500):**
```json
{
  "success": false,
  "message": "Error syncing attendances: [error message]",
  "data": null
}
```

## Business Logic

### Attendance Processing

The sync endpoint uses intelligent logic to process attendance entries:

1. **Clock-In Logic:**
   - If user has no open attendance → Creates new clock-in record
   - If user has open Regular attendance and new entry is Regular → Treats as clock-out
   - Validates business rules (weekend/holiday restrictions)

2. **Clock-Out Logic:**
   - If user has open attendance → Updates with clock-out time
   - Enforces minimum 2-minute duration between clock-in and clock-out
   - Automatically adjusts clock-out time if too early (for offline entries)

### Business Rules

1. **Weekend Restrictions:**
   - Regular attendance not allowed on weekends
   - Must use "Overtime" type for weekend entries

2. **Holiday Restrictions:**
   - Regular attendance not allowed on holidays
   - Must use "Overtime" type for holiday entries

3. **Minimum Duration:**
   - Clock-out must be at least 2 minutes after clock-in
   - Automatic adjustment for offline entries

4. **Active Shift Requirement:**
   - User must have an active employee shift
   - Shift information used for time calculations

## Error Handling

### Common Error Scenarios

1. **User Not Found:**
   - User ID doesn't exist
   - User is inactive
   - Returns 404 status

2. **Invalid Data Format:**
   - Missing required fields
   - Invalid datetime format
   - Returns 422 status

3. **Business Rule Violations:**
   - Weekend/holiday restrictions
   - Missing active shift
   - Returns 422 status

4. **Server Errors:**
   - Database connection issues
   - Unexpected exceptions
   - Returns 500 status

## Rate Limiting

No specific rate limiting is implemented, but consider implementing appropriate limits based on your application requirements.

## CORS

Ensure proper CORS headers are configured for cross-origin requests from your PWA.

## BO_React Application Integration

### **Offline-First Workflow**

The BO_React application follows an offline-first approach:

1. **Local Storage**: All data is stored locally using localStorage
2. **Offline Operations**: Users can record attendance without internet connection
3. **Batch Synchronization**: Multiple records are synced together when online
4. **Partial Success Handling**: Failed records remain in local storage for retry

### **State Management**

The application uses Zustand for reactive state management:
- **Attendance Store**: Manages attendance records and counts
- **Users Store**: Manages active user data
- **Real-time Updates**: UI automatically updates when data changes

### **Error Handling**

The application provides user-friendly error messages:
- **Weekend/Holiday Restrictions**: Clear messages about attendance type requirements
- **User-Specific Errors**: Shows actual user names instead of IDs
- **Partial Success**: Detailed breakdown of successful vs failed records

## Examples

### Complete BO_React Workflow

1. **Authorization (for sensitive operations):**
```bash
curl -X POST "http://blueorange.test/api/offline-attendance/authorize" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@company.com",
    "password": "password123"
  }'
```

2. **Get Active Users:**
```bash
curl -X GET "http://blueorange.test/api/offline-attendance/users" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

3. **Check User Status:**
```bash
curl -X GET "http://blueorange.test/api/offline-attendance/user/EMP001/status" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

4. **Sync Offline Attendances:**
```bash
curl -X POST "http://blueorange.test/api/offline-attendance/sync" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "attendances": [
      {
        "user_id": 123,
        "entry_date_time": "2024-01-15T09:00:00.000000Z",
        "type": "Regular"
      }
    ]
  }'
```

### **BO_React Frontend Integration**

The application handles API responses intelligently:

```typescript
// Example: Handling partial success response
if (result.success || result.syncedCount > 0) {
  // Mark only successfully synced records
  if (result.syncedRecordIds?.length > 0) {
    for (const recordIndex of result.syncedRecordIds) {
      await markAttendanceAsSynced(unsyncedAttendances[recordIndex].id);
    }
  }
  
  // Show user-friendly error messages
  if (result.errors?.length > 0) {
    const userFriendlyMessage = parseSyncError(result.message, result.errors);
    showToast(userFriendlyMessage, 'warning');
  }
}
```

## Notes

- All datetime fields use ISO 8601 format
- The API automatically handles timezone conversions
- Offline entries are processed with intelligent clock-in/clock-out logic
- Legacy format support ensures backward compatibility
- Error responses include detailed information for debugging
- The API is optimized for batch processing of attendance records

## Version

This documentation is for API version 1.0.

## Support

For technical support or questions about this API, please contact the development team.
