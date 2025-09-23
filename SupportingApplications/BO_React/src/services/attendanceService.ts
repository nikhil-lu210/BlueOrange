// Direct attendance service - handles all attendance operations
import { api } from '../utils/api'
import { DB_NAME } from '../utils/constants'

const ATTENDANCES_KEY = `${DB_NAME}_attendances`

export interface Attendance {
  id: number
  user_id: number
  entry_date_time: string
  type: 'Regular' | 'Overtime'
  synced: boolean
  created_at: string
  updated_at: string
}

class AttendanceService {
  private initialized = false

  async init(): Promise<void> {
    if (this.initialized) return

    // Initialize localStorage key if it doesn't exist
    if (!localStorage.getItem(ATTENDANCES_KEY)) {
      localStorage.setItem(ATTENDANCES_KEY, JSON.stringify([]))
    }

    this.initialized = true
  }

  // Get all attendances from localStorage
  private getAttendances(): Attendance[] {
    const data = localStorage.getItem(ATTENDANCES_KEY)
    return data ? JSON.parse(data) : []
  }

  // Save attendances to localStorage
  private saveAttendances(attendances: Attendance[]): void {
    localStorage.setItem(ATTENDANCES_KEY, JSON.stringify(attendances))
  }

  // Record a new attendance entry
  async recordEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<number> {
    await this.init()
    console.log('📝 AttendanceService: Recording entry for user:', userId, 'type:', type)

    const attendances = this.getAttendances()
    const newId = attendances.length > 0 ? Math.max(...attendances.map(a => a.id)) + 1 : 1

    const now = new Date()
    const attendance: Attendance = {
      id: newId,
      user_id: userId,
      entry_date_time: now.toISOString(),
      type,
      synced: false,
      created_at: now.toISOString(),
      updated_at: now.toISOString()
    }

    attendances.push(attendance)
    this.saveAttendances(attendances)

    console.log('✅ AttendanceService: Recorded entry with ID:', newId)
    return newId
  }

  // Get all attendances (sorted by newest first)
  async getAllAttendances(): Promise<Attendance[]> {
    await this.init()
    const attendances = this.getAttendances()
    return attendances.sort((a, b) => 
      new Date(b.entry_date_time).getTime() - new Date(a.entry_date_time).getTime()
    )
  }

  // Get attendances for a specific user
  async getUserEntries(userId: number): Promise<Attendance[]> {
    await this.init()
    const attendances = this.getAttendances()
    return attendances.filter(a => a.user_id === userId)
  }

  // Get unsynced attendances
  async getUnsyncedAttendances(): Promise<Attendance[]> {
    await this.init()
    const attendances = this.getAttendances()
    return attendances.filter(a => !a.synced)
  }

  // Mark attendance as synced
  async markAttendanceAsSynced(id: number): Promise<void> {
    await this.init()
    console.log('🔄 AttendanceService: Marking attendance as synced:', id)

    const attendances = this.getAttendances()
    const attendance = attendances.find(a => a.id === id)

    if (attendance) {
      attendance.synced = true
      attendance.updated_at = new Date().toISOString()
      this.saveAttendances(attendances)
      console.log('✅ AttendanceService: Marked attendance as synced:', id)
    }
  }

  // Delete attendance
  async deleteAttendance(id: number): Promise<void> {
    await this.init()
    console.log('🗑️ AttendanceService: Deleting attendance:', id)

    const attendances = this.getAttendances()
    const filtered = attendances.filter(a => a.id !== id)
    this.saveAttendances(filtered)

    console.log('✅ AttendanceService: Deleted attendance:', id)
  }

  // Clear all attendances
  async clearAllAttendances(): Promise<void> {
    await this.init()
    this.saveAttendances([])
    console.log('🗑️ AttendanceService: Cleared all attendances')
  }

  // Get attendance statistics
  async getStats(): Promise<{ totalAttendances: number; unsyncedAttendances: number }> {
    await this.init()
    const attendances = this.getAttendances()
    const unsynced = attendances.filter(a => !a.synced)

    return {
      totalAttendances: attendances.length,
      unsyncedAttendances: unsynced.length
    }
  }

  // Sync attendances to server
  async syncToServer(): Promise<{ success: boolean; message: string; syncedCount: number; errors?: string[] }> {
    await this.init()
    console.log('🌐 AttendanceService: Syncing attendances to server...')

    try {
      const unsyncedAttendances = await this.getUnsyncedAttendances()

      if (unsyncedAttendances.length === 0) {
        return {
          success: true,
          message: 'No unsynced attendances to upload',
          syncedCount: 0,
          errors: []
        }
      }

      console.log('📤 AttendanceService: Syncing', unsyncedAttendances.length, 'attendances')

      const result = await api.syncAttendances(unsyncedAttendances)

      if (result.success || result.syncedCount > 0) {
        // Mark synced records as synced
        if (result.syncedRecordIds && result.syncedRecordIds.length > 0) {
          for (const recordIndex of result.syncedRecordIds) {
            const attendance = unsyncedAttendances[recordIndex]
            if (attendance) {
              await this.markAttendanceAsSynced(attendance.id)
            }
          }
        }

        console.log('✅ AttendanceService: Sync completed, synced', result.syncedCount, 'records')
      }

      return {
        success: result.success || result.syncedCount > 0,
        message: result.message || 'Sync completed',
        syncedCount: result.syncedCount || 0,
        errors: result.errors || []
      }
    } catch (error: any) {
      console.error('❌ AttendanceService: Sync failed:', error)
      return {
        success: false,
        message: `Sync failed: ${error.message}`,
        syncedCount: 0,
        errors: [error.message]
      }
    }
  }

  // Determine attendance type based on current day
  determineAttendanceType(): 'Regular' | 'Overtime' {
    const today = new Date()
    const dayOfWeek = today.getDay() // 0 = Sunday, 6 = Saturday
    if (dayOfWeek === 0 || dayOfWeek === 6) return 'Overtime'
    return 'Regular'
  }
}

export const attendanceService = new AttendanceService()
export default attendanceService
