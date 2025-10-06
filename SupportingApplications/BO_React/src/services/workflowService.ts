// Workflow service (React) for offline-first attendance management
import { userService } from './userService'
import { attendanceService } from './attendanceService'
import { api } from '../utils/api'
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
    await userService.init()
    await attendanceService.init()

    const userCount = await userService.getUserCount()
    const attendanceStats = await attendanceService.getStats()
    const openAttendances = await attendanceService.getUnsyncedAttendances()
    const hasOpenAttendances = openAttendances.length > 0

    this.isInitialized = true

    return {
      isInitialized: true,
      hasUsers: userCount > 0,
      hasOpenAttendances,
      lastSyncTime: this.lastSyncTime,
      totalUsers: userCount,
      totalAttendances: attendanceStats.totalAttendances,
      unsyncedAttendances: attendanceStats.unsyncedAttendances
    }
  }

  async syncActiveUsers(onProgress?: (progress: SyncProgress) => void): Promise<WorkflowStatus> {
    if (onProgress) {
      onProgress({ step: 'clear', progress: 25, message: 'Clearing local user data...', isComplete: false })
    }

    if (onProgress) {
      onProgress({ step: 'users', progress: 50, message: `Fetching active users from ${getServerName()}...`, isComplete: false })
    }

    try {
      const syncedUsers = await userService.syncUsersFromAPI()

      if (onProgress) {
        onProgress({ step: 'complete', progress: 100, message: `Active users sync completed! ${syncedUsers.length} users synced.`, isComplete: true })
      }

      this.lastSyncTime = new Date().toISOString()
      return this.initializeApp()
    } catch (error: any) {
      throw new Error(`Failed to sync users: ${error.message}`)
    }
  }

  async handleUserScan(userid: string): Promise<{ user: any | null; action: 'record_entry' | 'not_found'; message: string }> {
    const user = await userService.getUserByUserid(userid)

    if (!user) {
      return { user: null, action: 'not_found', message: `User not found in local database. Please sync with ${getServerName()} first.` }
    }

    const type = attendanceService.determineAttendanceType()
    return { user: { ...user, suggestedType: type }, action: 'record_entry', message: `${user.alias_name} found. Ready to record ${type} entry.` }
  }

  async recordUserEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<{ success: boolean; message: string; attendanceId?: number }> {
    const user = await userService.getUserById(userId)
    if (!user) {
      return { success: false, message: 'User not found' }
    }
    const attendanceId = await attendanceService.recordEntry(userId, type)
    return { success: true, message: `${user.alias_name} entry recorded successfully`, attendanceId }
  }

  async syncOfflineData(): Promise<{ success: boolean; message: string; syncedCount: number; errors?: string[] }> {
    const result = await attendanceService.syncToServer()
    if (result.success) {
      this.lastSyncTime = new Date().toISOString()
    }
    return result
  }

  async getWorkflowStatus(): Promise<WorkflowStatus> {
    const userCount = await userService.getUserCount()
    const attendanceStats = await attendanceService.getStats()
    const openAttendances = await attendanceService.getUnsyncedAttendances()
    const hasOpenAttendances = openAttendances.length > 0

    return {
      isInitialized: this.isInitialized,
      hasUsers: userCount > 0,
      hasOpenAttendances,
      lastSyncTime: this.lastSyncTime,
      totalUsers: userCount,
      totalAttendances: attendanceStats.totalAttendances,
      unsyncedAttendances: attendanceStats.unsyncedAttendances
    }
  }

  async clearAllData(): Promise<void> {
    await userService.clearAllUsers()
    await attendanceService.clearAllAttendances()
    this.lastSyncTime = null
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
