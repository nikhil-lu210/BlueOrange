import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { smartDbService as dbService } from '@/services/smartDb'
import { API } from '@/utils/api'

export const useAttendanceStore = defineStore('attendance', () => {
  // State
  const attendances = ref<any[]>([])

  // Getters
  const unsyncedCount = computed(() =>
    attendances.value.filter((a: any) => !a.synced).length
  )

  const totalCount = computed(() => attendances.value.length)

  const syncedCount = computed(() =>
    attendances.value.filter((a: any) => a.synced).length
  )

  // Actions
  const loadAttendances = async () => {
    try {
      attendances.value = await dbService.getAllAttendances()
    } catch (error) {
      console.error('Failed to load attendances:', error)
      throw error
    }
  }

  const addAttendance = async (attendanceData: any) => {
    try {
      // This method is now handled by the dbService.clockIn/clockOut methods
      // This is kept for compatibility but should not be used directly
      await loadAttendances() // Reload to get updated list
      return attendanceData
    } catch (error) {
      console.error('Failed to add attendance:', error)
      throw error
    }
  }

  const syncAttendances = async () => {
    try {
      const unsynced = await dbService.getUnsyncedAttendances()

      if (unsynced.length === 0) {
        return {
          success: true,
          syncedCount: 0,
          totalCount: 0,
          message: 'No records to sync'
        }
      }

      const result = await API.syncAttendances(unsynced)

      if (result.success) {
        // Mark as synced
        for (const attendance of unsynced.slice(0, result.syncedCount)) {
          await dbService.markAttendanceAsSynced(attendance.id)
        }

        await loadAttendances() // Reload to get updated list
      }

      return result
    } catch (error) {
      console.error('Failed to sync attendances:', error)
      throw error
    }
  }

  const deleteAttendance = async (id: number) => {
    try {
      await dbService.deleteAttendance(id)
      await loadAttendances()
    } catch (error) {
      console.error('Failed to delete attendance:', error)
      throw error
    }
  }

  const clearAll = async () => {
    try {
      await dbService.clearAllAttendances()
      await loadAttendances()
    } catch (error) {
      console.error('Failed to clear attendances:', error)
      throw error
    }
  }

  return {
    // State
    attendances,
    // Getters
    unsyncedCount,
    totalCount,
    syncedCount,
    // Actions
    loadAttendances,
    addAttendance,
    syncAttendances,
    deleteAttendance,
    clearAll
  }
})
