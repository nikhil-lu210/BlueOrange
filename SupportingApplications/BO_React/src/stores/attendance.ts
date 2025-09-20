import { create } from 'zustand'
import { smartDbService as dbService } from '../services/smartDb'
import { API } from '../utils/api'
import type { Attendance, SyncResult } from '../types'

interface AttendanceState {
  attendances: Attendance[]
  unsyncedCount: number
  totalCount: number
  syncedCount: number
  loadAttendances: () => Promise<void>
  addAttendance: (attendanceData: Attendance) => Promise<Attendance>
  syncAttendances: () => Promise<SyncResult>
  deleteAttendance: (id: number) => Promise<void>
  clearAll: () => Promise<void>
}

export const useAttendanceStore = create<AttendanceState>((set, get) => ({
  attendances: [],
  unsyncedCount: 0,
  totalCount: 0,
  syncedCount: 0,

         loadAttendances: async () => {
           const data = await dbService.getAllAttendances()
           set({
             attendances: data,
             totalCount: data.length,
             unsyncedCount: data.filter((a: Attendance) => !a.synced).length,
             syncedCount: data.filter((a: Attendance) => a.synced).length
           })
         },

  addAttendance: async (attendanceData: Attendance) => {
    // This method is used for compatibility, but actual attendance recording
    // is done through the workflow service
    await get().loadAttendances()
    return attendanceData
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

}))
