<template>
  <div class="attendance-panel p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="mb-0">
        <i class="bi bi-table me-2"></i>
        Attendance Records
      </h5>
      <div class="d-flex align-items-center gap-2">
        <button
          v-if="attendances.length > 0"
          class="btn btn-outline-danger btn-sm"
          @click="clearAllAttendances"
          :disabled="loading"
          title="Clear all attendance records"
        >
          <i class="bi bi-trash"></i>
          Clear All
        </button>
        <div v-if="loading" class="loading-spinner"></div>
      </div>
    </div>

    <div v-if="attendances.length === 0" class="text-center text-muted py-4">
      <i class="bi bi-inbox display-4 d-block mb-2"></i>
      <p class="mb-0">No attendance records found</p>
      <small>Scan a barcode to start recording attendance</small>
    </div>

    <div v-else class="table-responsive">
      <table class="table table-attendance">
        <thead>
          <tr>
            <th>User</th>
            <th>Type</th>
            <th>Entry Time</th>
            <th>Synced</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="attendance in attendances" :key="attendance.id">
            <td>
              <div>
                <div class="fw-medium">{{ getUserInfo(attendance.user_id)?.alias_name || 'Unknown User' }}</div>
                <small class="text-muted">{{ getUserInfo(attendance.user_id)?.userid || `ID: ${attendance.user_id}` }}</small>
              </div>
            </td>
            <td>
              <span class="badge" :class="attendance.type === 'Overtime' ? 'bg-warning' : 'bg-primary'">
                {{ attendance.type || 'Regular' }}
              </span>
            </td>
            <td>
              <div>
                <div>{{ formatDate(attendance.entry_date_time) }}</div>
                <small class="text-muted">
                  {{ formatTime(attendance.entry_date_time) }}
                </small>
              </div>
            </td>
            <td>
              <span class="badge" :class="attendance.synced ? 'badge-synced' : 'badge-pending'">
                <i class="bi" :class="attendance.synced ? 'bi-check-circle' : 'bi-clock-history'"></i>
                {{ attendance.synced ? 'Synced' : 'Pending' }}
              </span>
            </td>
            <td>
              <button
                class="btn btn-outline-danger btn-sm"
                @click="deleteAttendance(attendance.id)"
                :disabled="loading"
                title="Delete record"
              >
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { smartDbService } from '@/services/smartDb'

interface Attendance {
  id: number
  user_id: number
  entry_date_time: string
  type: 'Regular' | 'Overtime'
  synced: boolean
  created_at: string
}

interface User {
  id: number
  userid: string
  name: string
  alias_name: string
}

interface Props {
  attendances: Attendance[]
  loading: boolean
}

interface Emits {
  (e: 'delete-attendance', id: number): void
  (e: 'clear-all-attendances'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Load users for display
const users = ref<User[]>([])

const loadUsers = async () => {
  try {
    users.value = await smartDbService.getAllUsers()
  } catch (error) {
    console.error('Failed to load users:', error)
  }
}

// Load users when component mounts
loadUsers()

const getUserInfo = (userId: number): User | undefined => {
  return users.value.find(user => user.id === userId)
}

const formatDate = (dateString: string): string => {
  try {
    return new Date(dateString).toLocaleDateString()
  } catch (error) {
    return 'Invalid Date'
  }
}

const formatTime = (timeString: string): string => {
  try {
    // Handle both ISO datetime strings and time-only strings
    const date = new Date(timeString)
    if (isNaN(date.getTime())) {
      // If it's not a valid date, try to parse as time only
      return timeString
    }
    return date.toLocaleTimeString()
  } catch (error) {
    return 'Invalid Time'
  }
}

const deleteAttendance = (id: number) => {
  if (confirm('Are you sure you want to delete this attendance record?')) {
    emit('delete-attendance', id)
  }
}

const clearAllAttendances = () => {
  if (confirm('Are you sure you want to clear ALL attendance records? This action cannot be undone.')) {
    emit('clear-all-attendances')
  }
}
</script>
