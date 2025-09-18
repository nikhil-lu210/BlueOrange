<template>
  <div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card p-3">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="bi bi-people"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <div class="text-muted small">Total Active Users</div>
            <div class="h5 mb-0">{{ users }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card p-3">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="bi bi-clock-history"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <div class="text-muted small">Total Records</div>
            <div class="h5 mb-0">{{ total }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card p-3">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="bi bi-exclamation-triangle"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <div class="text-muted small">Pending Sync</div>
            <div class="h5 mb-0">{{ pending }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card p-3">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="bi bi-check-circle"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <div class="text-muted small">Synced</div>
            <div class="h5 mb-0">{{ synced }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  total: number
  pending: number
  synced: number
  users: number
}

defineProps<Props>()
</script>
