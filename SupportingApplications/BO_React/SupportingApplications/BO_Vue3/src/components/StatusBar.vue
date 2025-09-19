<template>
  <div class="status-bar">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <i class="bi bi-info-circle me-2"></i>
        <span class="fw-medium">{{ status }}</span>
      </div>
      <div v-if="loading" class="loading-spinner"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  status: string
  type?: 'info' | 'success' | 'warning' | 'error' | 'loading'
  loading?: boolean
}

withDefaults(defineProps<Props>(), {
  type: 'info',
  loading: false
})
</script>
