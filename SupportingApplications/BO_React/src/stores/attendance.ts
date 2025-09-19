import { create } from 'zustand'
import { smartDbService as dbService } from '../services/smartDb'
import { API } from '../utils/api'
import type { Attendance, SyncResult } from '../types'

interface AttendanceState {
  attendances: Attendance[]
  loadAttendances: () => Promise<void>
  addAttendance: (attendanceData: Attendance) => Promise<Attendance>
  syncAttendances: () => Promise<SyncResult>
  deleteAttendance: (id: number) => Promise<void>
  clearAll: () => Promise<void>
  // Computed values - these will be calculated from attendances array
  get unsyncedCount(): number
  get totalCount(): number
  get syncedCount(): number
}

export const useAttendanceStore = create<AttendanceState>((set, get) => ({
  attendances: [],

  loadAttendances: async () => {
    const data = await dbService.getAllAttendances()
    set({ attendances: data })
  },

  addAttendance: async (attendanceData: Attendance) => {
    // Save the attendance data to the database
    const savedAttendance = await dbService.addAttendance(attendanceData)
    // Reload the attendances to update the state
    await get().loadAttendances()
    return savedAttendance
  },

  syncAttendances: async (): Promise<SyncResult> => {
    const unsynced = await dbService.getUnsyncedAttendances()
    if (unsynced.length === 0) {
      return {
        success: true,
        syncedCount: 0,
        totalCount: 0,
        message: 'No records to sync'
      }
    }

    const api = new API()
    const result = await api.syncAttendances(unsynced)

    if (result.success) {
      for (const attendance of unsynced.slice(0, result.syncedCount)) {
        await dbService.markAttendanceAsSynced(attendance.id)
      }
      await get().loadAttendances()
    }

    // Ensure result matches SyncResult type
    return {
      success: result.success,
      message: result.message || 'Sync completed',
      syncedCount: result.syncedCount || 0,
      totalCount: result.totalCount || 0,
      errors: result.errors
    }
  },

  deleteAttendance: async (id: number) => {
    await dbService.deleteAttendance(id)
    await get().loadAttendances()
  },

  clearAll: async () => {
    await dbService.clearAllAttendances()
    await get().loadAttendances()
  },

  // Computed getters - these will automatically update when attendances change
  get unsyncedCount() {
    return get().attendances.filter((a: Attendance) => !a.synced).length
  },

  get totalCount() {
    return get().attendances.length
  },

  get syncedCount() {
    return get().attendances.filter((a: Attendance) => a.synced).length
  }
}))
