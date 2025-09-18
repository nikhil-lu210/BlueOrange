// Complete workflow service for offline-first attendance management
import { smartDbService as dbService } from './smartDb'
import { syncService } from './syncService'
import { APP_NAME, getServerName } from '../utils/constants'

export interface WorkflowStatus {
  isInitialized: boolean
  hasUsers: boolean
  hasOpenAttendances: boolean
  lastSyncTime: string | null
  totalUsers: number
  totalAttendances: number
  unsyncedAttendances: number
}

export interface SyncProgress {
  step: string
  progress: number
  message: string
  isComplete: boolean
}

class WorkflowService {
  private isInitialized = false
  private lastSyncTime: string | null = null

  async initializeApp(): Promise<WorkflowStatus> {
    try {
      console.log(`🚀 Initializing ${APP_NAME}...`)

      // Step 1: Initialize database
      await dbService.init()

      // Step 2: Check current data status
      const stats = await dbService.getStats()

      // Step 3: Check if we have users
      const hasUsers = stats.totalUsers > 0

      // Step 4: Check if we have open attendances
      const openAttendances = await dbService.getUnsyncedAttendances()
      const hasOpenAttendances = openAttendances.some(att => !att.clock_out)

      this.isInitialized = true

      const status: WorkflowStatus = {
        isInitialized: true,
        hasUsers,
        hasOpenAttendances,
        lastSyncTime: this.lastSyncTime,
        totalUsers: stats.totalUsers,
        totalAttendances: stats.totalAttendances,
        unsyncedAttendances: stats.unsyncedAttendances
      }

      console.log('✅ App initialized successfully:', status)
      return status

    } catch (error) {
      console.error('❌ Failed to initialize app:', error)
      throw error
    }
  }

  async syncActiveUsers(onProgress?: (progress: SyncProgress) => void): Promise<WorkflowStatus> {
    try {
      console.log(`🔄 Starting active users sync from ${getServerName()}...`)

      // Step 1: Clear local user data
      if (onProgress) {
        onProgress({
          step: 'clear',
          progress: 25,
          message: 'Clearing local user data...',
          isComplete: false
        })
      }

      await dbService.clearAllUsers()

      // Step 2: Fetch fresh active users from BlueOrange
      if (onProgress) {
        onProgress({
          step: 'users',
          progress: 50,
          message: `Fetching active users from ${getServerName()}...`,
          isComplete: false
        })
      }

      const usersResult = await syncService.syncActiveUsers()

      if (!usersResult.success) {
        throw new Error(`Failed to sync users: ${usersResult.message}`)
      }

      // Step 3: Finalize
      if (onProgress) {
        onProgress({
          step: 'complete',
          progress: 100,
          message: `Active users sync completed! ${usersResult.usersSynced} users synced.`,
          isComplete: true
        })
      }

      this.lastSyncTime = new Date().toISOString()

      // Return updated status
      return await this.initializeApp()

    } catch (error) {
      console.error('❌ Active users sync failed:', error)
      throw error
    }
  }

  async handleUserScan(userid: string): Promise<{
    user: any | null
    action: 'record_entry' | 'not_found'
    message: string
  }> {
    try {
      console.log(`🔍 Scanning user: ${userid}`)

      // Step 1: Look up user in local database
      const user = await dbService.getUserByUserid(userid)

      if (!user) {
        return {
          user: null,
          action: 'not_found',
          message: `User not found in local database. Please sync with ${getServerName()} first.`
        }
      }

      // Step 2: Determine type based on date (holiday/weekend logic)
      const type = this.determineAttendanceType()

      return {
        user: { ...user, suggestedType: type },
        action: 'record_entry',
        message: `${user.alias_name} found. Ready to record ${type} entry.`
      }

    } catch (error) {
      console.error('❌ Error handling user scan:', error)
      throw error
    }
  }

  private determineAttendanceType(): 'Regular' | 'Overtime' {
    const today = new Date()
    const dayOfWeek = today.getDay() // 0 = Sunday, 6 = Saturday

    // Simple logic: Weekend = Overtime, Weekday = Regular
    // This can be enhanced with holiday checking later
    if (dayOfWeek === 0 || dayOfWeek === 6) {
      return 'Overtime'
    }

    return 'Regular'
  }

  async recordUserEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<{
    success: boolean
    message: string
    attendanceId?: number
  }> {
    try {
      console.log(`📝 Recording entry for user ${userId} (${type})`)

      // Step 1: Validate user exists
      const user = await dbService.getUserById(userId)
      if (!user) {
        throw new Error('User not found')
      }

      // Step 2: Record the entry
      const attendanceId = await dbService.recordEntry(userId, type)

      console.log(`✅ Entry recorded for ${user.alias_name} successfully`)

      return {
        success: true,
        message: `${user.alias_name} entry recorded successfully`,
        attendanceId
      }

    } catch (error) {
      console.error('❌ Entry recording failed:', error)
      return {
        success: false,
        message: error instanceof Error ? error.message : 'Entry recording failed'
      }
    }
  }


  async syncOfflineData(): Promise<{
    success: boolean
    message: string
    syncedCount: number
    errors?: string[]
  }> {
    try {
      console.log(`🔄 Syncing offline data to ${getServerName()}...`)

      const result = await syncService.syncOfflineAttendances()

      if (result.success) {
        this.lastSyncTime = new Date().toISOString()
        console.log(`✅ Synced ${result.attendancesSynced} attendances to ${getServerName()}`)
      }

      return {
        success: result.success,
        message: result.message,
        syncedCount: result.attendancesSynced || 0,
        errors: result.errors
      }

    } catch (error) {
      console.error('❌ Sync failed:', error)
      return {
        success: false,
        message: error instanceof Error ? error.message : 'Sync failed',
        syncedCount: 0
      }
    }
  }

  async getWorkflowStatus(): Promise<WorkflowStatus> {
    try {
      const stats = await dbService.getStats()
      const openAttendances = await dbService.getUnsyncedAttendances()
      const hasOpenAttendances = openAttendances.some(att => !att.clock_out)

      return {
        isInitialized: this.isInitialized,
        hasUsers: stats.totalUsers > 0,
        hasOpenAttendances,
        lastSyncTime: this.lastSyncTime,
        totalUsers: stats.totalUsers,
        totalAttendances: stats.totalAttendances,
        unsyncedAttendances: stats.unsyncedAttendances
      }
    } catch (error) {
      console.error('❌ Failed to get workflow status:', error)
      return {
        isInitialized: false,
        hasUsers: false,
        hasOpenAttendances: false,
        lastSyncTime: null,
        totalUsers: 0,
        totalAttendances: 0,
        unsyncedAttendances: 0
      }
    }
  }

  async clearAllData(): Promise<void> {
    try {
      console.log('🗑️ Clearing all data...')
      await dbService.clearAllData()
      this.lastSyncTime = null
      console.log('✅ All data cleared')
    } catch (error) {
      console.error('❌ Failed to clear data:', error)
      throw error
    }
  }

  isAppReady(): boolean {
    return this.isInitialized
  }

  getLastSyncTime(): string | null {
    return this.lastSyncTime
  }
}

export const workflowService = new WorkflowService()
export default workflowService
