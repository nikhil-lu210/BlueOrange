// Workflow service (React) for offline-first attendance management
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
    await dbService.init()

    const stats = await dbService.getStats()
    const openAttendances = await dbService.getUnsyncedAttendances()
    const hasOpenAttendances = openAttendances.some((att: any) => !att.clock_out)

    this.isInitialized = true

    return {
      isInitialized: true,
      hasUsers: stats.totalUsers > 0,
      hasOpenAttendances,
      lastSyncTime: this.lastSyncTime,
      totalUsers: stats.totalUsers,
      totalAttendances: stats.totalAttendances,
      unsyncedAttendances: stats.unsyncedAttendances
    }
  }

  async syncActiveUsers(onProgress?: (progress: SyncProgress) => void): Promise<WorkflowStatus> {
    if (onProgress) {
      onProgress({ step: 'clear', progress: 25, message: 'Clearing local user data...', isComplete: false })
    }

    await dbService.clearAllUsers()

    if (onProgress) {
      onProgress({ step: 'users', progress: 50, message: `Fetching active users from ${getServerName()}...`, isComplete: false })
    }

    const usersResult = await syncService.syncActiveUsers()
    if (!usersResult.success) {
      throw new Error(`Failed to sync users: ${usersResult.message}`)
    }

    if (onProgress) {
      onProgress({ step: 'complete', progress: 100, message: `Active users sync completed! ${usersResult.usersSynced} users synced.`, isComplete: true })
    }

    this.lastSyncTime = new Date().toISOString()

    return this.initializeApp()
  }

  async handleUserScan(userid: string): Promise<{ user: any | null; action: 'record_entry' | 'not_found'; message: string }> {
    const user = await dbService.getUserByUserid(userid)

    if (!user) {
      return { user: null, action: 'not_found', message: `User not found in local database. Please sync with ${getServerName()} first.` }
    }

    const type = this.determineAttendanceType()
    return { user: { ...user, suggestedType: type }, action: 'record_entry', message: `${user.alias_name} found. Ready to record ${type} entry.` }
  }

  private determineAttendanceType(): 'Regular' | 'Overtime' {
    const today = new Date()
    const dayOfWeek = today.getDay() // 0 = Sunday, 6 = Saturday
    if (dayOfWeek === 0 || dayOfWeek === 6) return 'Overtime'
    return 'Regular'
  }

  async recordUserEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<{ success: boolean; message: string; attendanceId?: number }> {
    const user = await dbService.getUserById(userId)
    if (!user) {
      return { success: false, message: 'User not found' }
    }
    const attendanceId = await dbService.recordEntry(userId, type)
    return { success: true, message: `${user.alias_name} entry recorded successfully`, attendanceId }
  }

  async syncOfflineData(): Promise<{ success: boolean; message: string; syncedCount: number; errors?: string[] }> {
    const result = await syncService.syncOfflineAttendances()
    if (result.success) {
      this.lastSyncTime = new Date().toISOString()
    }
    return { success: result.success, message: result.message, syncedCount: result.attendancesSynced || 0, errors: result.errors }
  }

  async getWorkflowStatus(): Promise<WorkflowStatus> {
    const stats = await dbService.getStats()
    const openAttendances = await dbService.getUnsyncedAttendances()
    const hasOpenAttendances = openAttendances.some((att: any) => !att.clock_out)

    return {
      isInitialized: this.isInitialized,
      hasUsers: stats.totalUsers > 0,
      hasOpenAttendances,
      lastSyncTime: this.lastSyncTime,
      totalUsers: stats.totalUsers,
      totalAttendances: stats.totalAttendances,
      unsyncedAttendances: stats.unsyncedAttendances
    }
  }

  async clearAllData(): Promise<void> {
    await dbService.clearAllData()
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
