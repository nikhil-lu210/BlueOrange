// Sync service for server integration (React)
import { smartDbService as dbService } from './smartDb'
import { API } from '../utils/api'
import { getServerName } from '../utils/constants'

export interface SyncResult {
  success: boolean
  message: string
  usersSynced?: number
  attendancesSynced?: number
  errors?: string[]
}

class SyncService {
  private isOnline = typeof navigator !== 'undefined' ? navigator.onLine : true

  constructor() {
    if (typeof window !== 'undefined') {
      window.addEventListener('online', () => {
        this.isOnline = true
      })
      window.addEventListener('offline', () => {
        this.isOnline = false
      })
    }
  }

  async syncActiveUsers(): Promise<SyncResult> {
    if (!this.isOnline) {
      return {
        success: false,
        message: 'No internet connection. Working offline with cached data.',
        usersSynced: 0,
        attendancesSynced: 0
      }
    }

    try {
      console.log(`Starting active users sync from ${getServerName()}...`)

      // Remove testConnection call to prevent unnecessary requests
      // The actual API call will handle connection errors

      const usersResult = await this.syncUsers()
      if (!usersResult.success) {
        return usersResult
      }

      return {
        success: true,
        message: `Active users sync completed: ${usersResult.usersSynced} users synced`,
        usersSynced: usersResult.usersSynced,
        attendancesSynced: 0,
        errors: usersResult.errors || []
      }
    } catch (error: any) {
      console.error('Error in syncActiveUsers:', error);
      return {
        success: false,
        message: `Sync failed: ${error?.message || 'Unknown error'}`,
        usersSynced: 0,
        attendancesSynced: 0
      }
    }
  }

  private async syncUsers(): Promise<SyncResult> {
    try {
      const api = new API()
      const users = await api.getAllUsers()

      if (!users || users.length === 0) {
        return { success: true, message: `No users found in ${getServerName()}`, usersSynced: 0 }
      }

      let syncedCount = 0
      const errors: string[] = []

      for (const user of users) {
        try {
          const existingUser = await dbService.getUserByUserid(user.userid)
          if (existingUser) {
            await dbService.updateUser(existingUser.id, {
              userid: user.userid,
              name: user.name,
              alias_name: user.alias_name
            })
          } else {
            await dbService.insertUser({
              userid: user.userid,
              name: user.name,
              alias_name: user.alias_name
            })
          }
          syncedCount++
        } catch (userError: any) {
          const errorMsg = `Failed to sync user ${user.userid}: ${userError?.message || 'Unknown error'}`
          errors.push(errorMsg)
          console.error(errorMsg, userError)
        }
      }

      return {
        success: true,
        message: `Synced ${syncedCount} users`,
        usersSynced: syncedCount,
        errors: errors.length > 0 ? errors : undefined
      }
    } catch (error: any) {
      console.error('Error in syncUsers:', error);
      return {
        success: false,
        message: `Failed to sync users: ${error?.message || 'Unknown error'}`,
        usersSynced: 0
      }
    }
  }

  async syncOfflineAttendances(): Promise<SyncResult> {
    if (!this.isOnline) {
      return {
        success: false,
        message: 'No internet connection. Cannot sync offline attendances.',
        attendancesSynced: 0
      }
    }

    try {
      const api = new API()
      // Remove testConnection call to prevent unnecessary requests
      // The actual API call will handle connection errors

      const unsyncedAttendances = await dbService.getUnsyncedAttendances()
      if (unsyncedAttendances.length === 0) {
        return { success: true, message: 'No offline attendances to sync', attendancesSynced: 0 }
      }

      const attendancesToSync = unsyncedAttendances.map((attendance: any) => ({
        user_id: attendance.user_id,
        entry_date_time: attendance.entry_date_time,
        type: attendance.type
      }))

      const result = await api.syncAttendances(attendancesToSync)

      // Handle both complete success and partial success (some records synced, some failed)
      if (result.success || result.syncedCount > 0) {
        // For partial success, we need to be more careful about which records to mark as synced
        // The backend processes records sequentially, so the first N records that didn't error should be marked as synced

        // Mark only the specifically synced records as synced in local database
        if (result.syncedRecordIds && result.syncedRecordIds.length > 0) {
          // Use the specific record IDs returned by the backend
          for (const recordIndex of result.syncedRecordIds) {
            if (recordIndex < unsyncedAttendances.length) {
              await dbService.markAttendanceAsSynced(unsyncedAttendances[recordIndex].id)
            }
          }
        } else if (result.success) {
          // Fallback: if no specific IDs but complete success, mark all records as synced
          for (const attendance of unsyncedAttendances) {
            await dbService.markAttendanceAsSynced(attendance.id)
          }
        }

        // Add a small delay to ensure localStorage operations complete
        await new Promise(resolve => setTimeout(resolve, 100));

        if (result.success) {
          return { success: true, message: `Successfully synced ${result.syncedCount} attendances`, attendancesSynced: result.syncedCount, errors: result.errors }
        } else {
          return { success: false, message: result.message || `Synced ${result.syncedCount} of ${result.totalCount} attendances`, attendancesSynced: result.syncedCount, errors: result.errors }
        }
      }

      return { success: false, message: result.message || 'Sync failed', attendancesSynced: 0, errors: result.errors }
    } catch (error: any) {
      return { success: false, message: `Failed to sync offline attendances: ${error?.message || 'Unknown error'}`, attendancesSynced: 0 }
    }
  }

  isConnected(): boolean {
    return this.isOnline
  }
}

export const syncService = new SyncService()
export default syncService
