<template>
  <div id="app">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <span class="navbar-brand">
          <i class="bi bi-clock-history me-2"></i>
          {{ appName }}
        </span>
        <div class="d-flex align-items-center">
          <span class="badge me-3" :class="isOnline ? 'bg-success' : 'bg-warning'">
            <i class="bi" :class="isOnline ? 'bi-wifi' : 'bi-wifi-off'"></i>
            {{ isOnline ? 'Online' : 'Offline' }}
          </span>
          <div class="btn-group">
            <button
              class="btn btn-outline-light btn-sm"
              @click="syncActiveUsers"
              :disabled="!isOnline || loading"
              :title="`Sync active users from ${getServerName()}`"
            >
              <i class="bi bi-cloud-download me-1"></i>
              Sync from {{ getServerName() }}
            </button>
            <button
              class="btn btn-outline-light btn-sm"
              @click="syncAttendances"
              :disabled="!isOnline || loading || unsyncedCount === 0"
              :title="`Sync offline attendances to ${getServerName()}`"
            >
              <i class="bi bi-cloud-arrow-up me-1"></i>
              Sync to {{ getServerName() }} ({{ unsyncedCount }})
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
      <!-- Status Bar -->
      <StatusBar
        :status="status"
        :type="statusType"
        :loading="loading"
      />

      <div class="row">
        <div class="col-md-12">
          <!-- Statistics -->
          <StatsCards
            :total="totalCount"
            :pending="unsyncedCount"
            :synced="syncedCount"
            :users="users.length"
          />
        </div>
      </div>

      <div class="row">
        <!-- Scanner Panel -->
        <div class="col-lg-4 col-md-6 mb-4">
          <Scanner
            :current-user="currentUser"
            :loading="loading"
            @user-scanned="handleUserScanned"
            @record-entry="handleRecordEntry"
            @clear-user="clearUser"
          />
        </div>

        <!-- Attendance Table -->
        <div class="col-lg-8 col-md-6">
          <AttendanceTable
            :attendances="attendances"
            :loading="loading"
            @delete-attendance="deleteAttendance"
            @clear-all-attendances="clearAllAttendances"
          />
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <Toast />
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { useAttendanceStore } from './stores/attendance'
import { useUserStore } from './stores/user'
import StatusBar from './components/StatusBar.vue'
import Scanner from './components/Scanner.vue'
import AttendanceTable from './components/AttendanceTable.vue'
import StatsCards from './components/StatsCards.vue'
import Toast from './components/Toast.vue'
import { smartDbService as dbService } from './services/smartDb'
import { syncService } from './services/syncService'
import { workflowService } from './services/workflowService'
import { APP_NAME, getServerName } from './utils/constants'

// Stores
const attendanceStore = useAttendanceStore()
const userStore = useUserStore()

// Reactive state
const isOnline = ref(navigator.onLine)
const currentUser = ref<any>(null)
const loading = ref(false)
const status = ref('Ready to scan attendance')
const statusType = ref<'info' | 'success' | 'warning' | 'error' | 'loading'>('info')

// Computed properties
const attendances = computed(() => attendanceStore.attendances)
const users = computed(() => userStore.users)
const unsyncedCount = computed(() => attendanceStore.unsyncedCount)
const totalCount = computed(() => attendanceStore.totalCount)
const syncedCount = computed(() => attendanceStore.syncedCount)
const appName = computed(() => APP_NAME)

// Methods
const updateStatus = (message: string, type: 'info' | 'success' | 'warning' | 'error' | 'loading' = 'info') => {
  status.value = message
  statusType.value = type
}

const handleUserScanned = async (userid: string) => {
  try {
    loading.value = true
    updateStatus('Looking up user...', 'loading')

    const result = await workflowService.handleUserScan(userid)

    if (result.action === 'not_found') {
      currentUser.value = null
      updateStatus(result.message, 'error')
      return
    }

    currentUser.value = result.user
    updateStatus(result.message, 'success')

  } catch (error) {
    console.error('Error scanning user:', error)
    currentUser.value = null
    updateStatus('Error looking up user', 'error')
  } finally {
    loading.value = false
  }
}

const handleRecordEntry = async (type: string) => {
  if (!currentUser.value) return

  try {
    loading.value = true

    const result = await workflowService.recordUserEntry(
      currentUser.value.id,
      type as 'Regular' | 'Overtime'
    )

    if (result.success) {
      updateStatus(result.message, 'success')
      currentUser.value = null
      await loadAttendanceData()
      ;(window as any).Toast?.show('Entry recorded successfully', 'success')
    } else {
      updateStatus(result.message, 'error')
      ;(window as any).Toast?.show(result.message, 'error')
    }
  } catch (error) {
    console.error('Error recording entry:', error)
    updateStatus('Failed to record entry', 'error')
    ;(window as any).Toast?.show('Failed to record entry', 'error')
  } finally {
    loading.value = false
  }
}

const syncAttendances = async () => {
  if (!isOnline.value) {
    updateStatus('No internet connection', 'error')
    return
  }

  try {
    loading.value = true
    updateStatus(`Syncing offline attendances to ${getServerName()}...`, 'loading')

    const result = await workflowService.syncOfflineData()

    if (result.success) {
      updateStatus(`Synced ${result.syncedCount} records to ${getServerName()}`, 'success')
      ;(window as any).Toast?.show(`Successfully synced ${result.syncedCount} records`, 'success')

      // Reload attendances to update sync status
      await loadAttendanceData()
    } else {
      updateStatus('Sync failed', 'error')
      ;(window as any).Toast?.show('Sync failed: ' + result.message, 'error')
    }
  } catch (error) {
    console.error('Error syncing:', error)
    updateStatus('Sync failed', 'error')
    ;(window as any).Toast?.show('Sync failed', 'error')
  } finally {
    loading.value = false
  }
}

const syncActiveUsers = async () => {
  if (!isOnline.value) {
    updateStatus('No internet connection. Working offline.', 'warning')
    return
  }

  try {
    loading.value = true
    updateStatus(`Syncing active users from ${getServerName()}...`, 'loading')

    const result = await workflowService.syncActiveUsers((progress) => {
      updateStatus(progress.message, 'loading')
    })

    updateStatus(`Active users sync completed: ${result.totalUsers} users synced`, 'success')
    ;(window as any).Toast?.show(`Synced ${result.totalUsers} active users from ${getServerName()}`, 'success')

    // Reload data
    await loadAttendanceData()
    await userStore.loadUsers()
  } catch (error) {
    console.error('Error in active users sync:', error)
    updateStatus('Active users sync failed', 'error')
    ;(window as any).Toast?.show('Active users sync failed', 'error')
  } finally {
    loading.value = false
  }
}

const deleteAttendance = async (id: number) => {
  try {
    await attendanceStore.deleteAttendance(id)
    ;(window as any).Toast?.show('Attendance record deleted', 'success')
  } catch (error) {
    console.error('Error deleting attendance:', error)
    ;(window as any).Toast?.show('Failed to delete attendance record', 'error')
  }
}

const clearAllAttendances = async () => {
  try {
    await attendanceStore.clearAll()
    ;(window as any).Toast?.show('All attendance records cleared', 'success')
    updateStatus('All attendance records cleared', 'success')
  } catch (error) {
    console.error('Error clearing attendances:', error)
    ;(window as any).Toast?.show('Failed to clear attendance records', 'error')
  }
}

const clearUser = () => {
  currentUser.value = null
  updateStatus('Ready to scan attendance', 'info')
}

// Network status handling
const updateOnlineStatus = () => {
  isOnline.value = navigator.onLine
  updateStatus(
    isOnline.value ? 'Connected to server' : 'Working offline',
    isOnline.value ? 'success' : 'warning'
  )
}

// Load attendance data from SQLite
const loadAttendanceData = async () => {
  try {
    const attendances = await dbService.getAllAttendances()
    attendanceStore.attendances = attendances
  } catch (error) {
    console.error('Failed to load attendance data:', error)
  }
}

// Initialize
onMounted(async () => {
  try {
    // Initialize the app using workflow service
    const status = await workflowService.initializeApp()

    // Load data from database
    await loadAttendanceData()
    await userStore.loadUsers()

    updateOnlineStatus()

    // Check if we need to sync active users
    if (isOnline.value && !status.hasUsers) {
      updateStatus(`No users found. Syncing active users from ${getServerName()}...`, 'loading')
      await syncActiveUsers()
    } else if (!status.hasUsers) {
      updateStatus(`No users found. Click "Sync from ${getServerName()}" when online.`, 'warning')
    } else {
      updateStatus(`Ready! ${status.totalUsers} users loaded.`, 'success')
    }

    // Listen for network changes
    window.addEventListener('online', updateOnlineStatus)
    window.addEventListener('offline', updateOnlineStatus)
  } catch (error) {
    console.error('Failed to initialize app:', error)
    updateStatus('Failed to initialize app', 'error')
  }
})

onUnmounted(() => {
  window.removeEventListener('online', updateOnlineStatus)
  window.removeEventListener('offline', updateOnlineStatus)
})
</script>
