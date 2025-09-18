<template>
  <div class="toast-container">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="toast show"
      :class="`toast-${toast.type}`"
      role="alert"
    >
      <div class="toast-header">
        <i class="bi me-2" :class="getIcon(toast.type)"></i>
        <strong class="me-auto">{{ getTitle(toast.type) }}</strong>
        <button
          type="button"
          class="btn-close"
          @click="removeToast(toast.id)"
        ></button>
      </div>
      <div class="toast-body">
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'

interface Toast {
  id: number
  type: 'success' | 'error' | 'warning' | 'info'
  message: string
}

const toasts = ref<Toast[]>([])

let nextId = 1

const show = (message: string, type: Toast['type'] = 'info') => {
  const toast: Toast = {
    id: nextId++,
    type,
    message
  }

  toasts.value.push(toast)

  // Auto remove after 5 seconds
  setTimeout(() => {
    removeToast(toast.id)
  }, 5000)
}

const removeToast = (id: number) => {
  const index = toasts.value.findIndex(t => t.id === id)
  if (index > -1) {
    toasts.value.splice(index, 1)
  }
}

const getIcon = (type: Toast['type']): string => {
  const icons = {
    success: 'bi-check-circle-fill text-success',
    error: 'bi-exclamation-triangle-fill text-danger',
    warning: 'bi-exclamation-triangle-fill text-warning',
    info: 'bi-info-circle-fill text-info'
  }
  return icons[type]
}

const getTitle = (type: Toast['type']): string => {
  const titles = {
    success: 'Success',
    error: 'Error',
    warning: 'Warning',
    info: 'Info'
  }
  return titles[type]
}

// Expose show method globally
window.Toast = { show }

defineExpose({
  show,
  removeToast
})
</script>
