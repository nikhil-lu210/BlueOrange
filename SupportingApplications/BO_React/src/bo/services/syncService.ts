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

      const api = new API()
      const isConnected = await api.testConnection()
      if (!isConnected) {
        return {
          success: false,
          message: `Cannot connect to ${getServerName()} server. Please check if the server is running and accessible.`,
          usersSynced: 0,
          attendancesSynced: 0
        }
      }

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
      const isConnected = await api.testConnection()
      if (!isConnected) {
        return {
          success: false,
          message: `Cannot connect to ${getServerName()} server. Please check if the server is running and accessible.`,
          attendancesSynced: 0
        }
      }

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

      if (result.success) {
        for (let i = 0; i < result.syncedCount; i++) {
          await dbService.markAttendanceAsSynced(unsyncedAttendances[i].id)
        }
        return { success: true, message: `Successfully synced ${result.syncedCount} attendances`, attendancesSynced: result.syncedCount, errors: result.errors }
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
