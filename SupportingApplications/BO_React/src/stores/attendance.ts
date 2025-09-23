import { create } from 'zustand'
import { attendanceService } from '../services/attendanceService'
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
        const data = await attendanceService.getAllAttendances()
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
    const result = await attendanceService.syncToServer()
    await get().loadAttendances()

    return {
      success: result.success,
      message: result.message,
      syncedCount: result.syncedCount,
      totalCount: result.syncedCount, // For compatibility
      errors: result.errors
    }
  },

  deleteAttendance: async (id: number) => {
    await attendanceService.deleteAttendance(id)
    await get().loadAttendances()
  },

  clearAll: async () => {
    await attendanceService.clearAllAttendances()
    await get().loadAttendances()
  },

}))
