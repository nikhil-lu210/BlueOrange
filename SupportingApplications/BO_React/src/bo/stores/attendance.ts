import { create } from 'zustand'
import { smartDbService as dbService } from '../services/smartDb'
import { API } from '../utils/api'

interface AttendanceState {
  attendances: any[]
  loadAttendances: () => Promise<void>
  addAttendance: (attendanceData: any) => Promise<any>
  syncAttendances: () => Promise<{ success: boolean; syncedCount: number; totalCount: number; message?: string; errors?: string[] }>
  deleteAttendance: (id: number) => Promise<void>
  clearAll: () => Promise<void>
  unsyncedCount: number
  totalCount: number
  syncedCount: number
}

export const useAttendanceStore = create<AttendanceState>((set, get) => ({
  attendances: [],

  loadAttendances: async () => {
    const data = await dbService.getAllAttendances()
    set({ attendances: data, totalCount: data.length, unsyncedCount: data.filter((a: any) => !a.synced).length, syncedCount: data.filter((a: any) => a.synced).length })
  },

  addAttendance: async (attendanceData: any) => {
    // Compatibility shim with Vue store
    await get().loadAttendances()
    return attendanceData
  },

  syncAttendances: async () => {
    const unsynced = await dbService.getUnsyncedAttendances()
    if (unsynced.length === 0) {
      return { success: true, syncedCount: 0, totalCount: 0, message: 'No records to sync' }
    }

    const api = new API()
    const result = await api.syncAttendances(unsynced)

    if (result.success) {
      for (const attendance of unsynced.slice(0, result.syncedCount)) {
        await dbService.markAttendanceAsSynced(attendance.id)
      }
      await get().loadAttendances()
    }

    return result
  },

  deleteAttendance: async (id: number) => {
    await dbService.deleteAttendance(id)
    await get().loadAttendances()
  },

  clearAll: async () => {
    await dbService.clearAllAttendances()
    await get().loadAttendances()
  },

  unsyncedCount: 0,
  totalCount: 0,
  syncedCount: 0
}))
