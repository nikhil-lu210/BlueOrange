// Sync service for server integration
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
  private isOnline = navigator.onLine

  constructor() {
    // Listen for network changes
    window.addEventListener('online', () => {
      this.isOnline = true
    })
    window.addEventListener('offline', () => {
      this.isOnline = false
    })
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

      // Test connection first
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

      // Only sync active users (no attendances)
      const usersResult = await this.syncUsers()
      if (!usersResult.success) {
        return usersResult
      }

      return {
        success: true,
        message: `Active users sync completed: ${usersResult.usersSynced} users synced`,
        usersSynced: usersResult.usersSynced,
        attendancesSynced: 0, // No attendances synced
        errors: usersResult.errors || []
      }
    } catch (error) {
      console.error('Active users sync failed:', error)
      return {
        success: false,
        message: `Sync failed: ${error instanceof Error ? error.message : 'Unknown error'}`,
        usersSynced: 0,
        attendancesSynced: 0
      }
    }
  }

  // Keep the old method for backward compatibility (deprecated)
  async syncInitialData(): Promise<SyncResult> {
    console.warn('syncInitialData is deprecated. Use syncActiveUsers instead.')
    return this.syncActiveUsers()
  }

  private async syncUsers(): Promise<SyncResult> {
    try {
      console.log(`Syncing users from ${getServerName()}...`)

      // Get all users from server API
      const api = new API()
      const users = await api.getAllUsers()

      if (!users || users.length === 0) {
        return {
          success: true,
          message: `No users found in ${getServerName()}`,
          usersSynced: 0
        }
      }

      let syncedCount = 0
      const errors: string[] = []

      // Store each user in local database
      for (const user of users) {
        try {
          // Check if user already exists
          const existingUser = await dbService.getUserByUserid(user.userid)

          if (existingUser) {
            // Update existing user
            await dbService.updateUser(existingUser.id, {
              userid: user.userid,
              name: user.name,
              alias_name: user.alias_name
            })
          } else {
            // Insert new user
            await dbService.insertUser({
              userid: user.userid,
              name: user.name,
              alias_name: user.alias_name
            })
          }

          syncedCount++
        } catch (userError) {
          const errorMsg = `Failed to sync user ${user.userid}: ${userError instanceof Error ? userError.message : 'Unknown error'}`
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
    } catch (error) {
      console.error('Failed to sync users:', error)
      return {
        success: false,
        message: `Failed to sync users: ${error instanceof Error ? error.message : 'Unknown error'}`,
        usersSynced: 0
      }
    }
  }

  private async syncOpenAttendances(): Promise<SyncResult> {
    try {
      console.log(`Syncing open attendances from ${getServerName()}...`)

      // Get open attendances from server API
      // This would need a new API endpoint to get open attendances
      // For now, we'll return success with 0 attendances
      // In a real implementation, you'd call something like:
      // const openAttendances = await api.getOpenAttendances()

      // TODO: Implement when server API endpoint is available
      // const api = new API()
      // const openAttendances = await api.getOpenAttendances()

      return {
        success: true,
        message: `Open attendances sync not implemented yet (requires ${getServerName()} API endpoint)`,
        attendancesSynced: 0
      }
    } catch (error) {
      console.error('Failed to sync open attendances:', error)
      return {
        success: false,
        message: `Failed to sync open attendances: ${error instanceof Error ? error.message : 'Unknown error'}`,
        attendancesSynced: 0
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
      console.log(`Syncing offline attendances to ${getServerName()}...`)

      // Test connection first
      const api = new API()
      const isConnected = await api.testConnection()
      if (!isConnected) {
        return {
          success: false,
          message: `Cannot connect to ${getServerName()} server. Please check if the server is running and accessible.`,
          attendancesSynced: 0
        }
      }

      // Get unsynced attendances from local database
      const unsyncedAttendances = await dbService.getUnsyncedAttendances()

      if (unsyncedAttendances.length === 0) {
        return {
          success: true,
          message: 'No offline attendances to sync',
          attendancesSynced: 0
        }
      }

      // Convert to simple server format - just entry records
      const attendancesToSync = unsyncedAttendances.map(attendance => {
        const formattedAttendance = {
          user_id: attendance.user_id,
          entry_date_time: attendance.entry_date_time,
          type: attendance.type
        }

        console.log(`📤 Sending attendance entry to ${getServerName()}:`, formattedAttendance)
        return formattedAttendance
      })

      // Send to server API
      const result = await api.syncAttendances(attendancesToSync)

      if (result.success) {
        // Mark attendances as synced
        for (let i = 0; i < result.syncedCount; i++) {
          await dbService.markAttendanceAsSynced(unsyncedAttendances[i].id)
        }

        return {
          success: true,
          message: `Successfully synced ${result.syncedCount} attendances`,
          attendancesSynced: result.syncedCount,
          errors: result.errors
        }
      } else {
        return {
          success: false,
          message: result.message || 'Sync failed',
          attendancesSynced: 0,
          errors: result.errors
        }
      }
    } catch (error) {
      console.error('Failed to sync offline attendances:', error)
      return {
        success: false,
        message: `Failed to sync offline attendances: ${error instanceof Error ? error.message : 'Unknown error'}`,
        attendancesSynced: 0
      }
    }
  }

  async validateAttendanceData(attendance: any): Promise<{ valid: boolean; errors: string[] }> {
    const errors: string[] = []

    // Validate required fields
    if (!attendance.userid) {
      errors.push('User ID is required')
    }
    if (!attendance.name) {
      errors.push('User name is required')
    }
    if (!attendance.clock_in) {
      errors.push('Clock in time is required')
    }

    // Validate user exists
    if (attendance.userid) {
      const user = await dbService.getUserByUserid(attendance.userid)
      if (!user) {
        errors.push(`User with ID ${attendance.userid} not found`)
      }
    }

    // Validate clock out logic
    if (attendance.clock_out && attendance.clock_in) {
      const clockIn = new Date(attendance.clock_in)
      const clockOut = new Date(attendance.clock_out)

      if (clockOut <= clockIn) {
        errors.push('Clock out time must be after clock in time')
      }
    }

    return {
      valid: errors.length === 0,
      errors
    }
  }

  isConnected(): boolean {
    return this.isOnline
  }
}

export const syncService = new SyncService()
export default syncService
