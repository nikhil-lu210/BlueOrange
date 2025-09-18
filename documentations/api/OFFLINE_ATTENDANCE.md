# Offline Attendance API Documentation

## Overview

The Offline Attendance API provides endpoints for managing attendance data in an offline-first Progressive Web Application (PWA). This API supports user lookup, attendance status checking, and synchronization of offline attendance records.

## Base URL

```
http://blueorange.test/api
```

## Authentication

All endpoints require proper authentication. Include authentication headers as per your application's authentication mechanism.

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
    "errors": [
      "Error processing attendance for 124: You cannot Regular Clock-In on Weekend. Please clock in as Overtime."
    ]
  }
}
```

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

## Examples

### Complete Workflow Example

1. **Get Active Users:**
```bash
curl -X GET "http://blueorange.test/api/offline-attendance/users" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

2. **Check User Status:**
```bash
curl -X GET "http://blueorange.test/api/offline-attendance/user/EMP001/status" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

3. **Sync Offline Attendances:**
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
