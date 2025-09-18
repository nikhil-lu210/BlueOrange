<template>
  <div class="scanner-panel p-4">
    <h5 class="mb-3">
      <i class="bi bi-qr-code-scan me-2"></i>
      Barcode Scanner
    </h5>

    <!-- Barcode Input -->
    <div class="mb-3">
      <label for="barcodeInput" class="form-label">Scan or Enter User ID</label>
      <input
        id="barcodeInput"
        ref="barcodeInput"
        v-model="barcodeValue"
        type="text"
        class="form-control barcode-input"
        placeholder="Scan barcode or enter user ID"
        :disabled="loading"
        :aria-describedby="loading ? 'loading-status' : 'input-help'"
        :aria-invalid="barcodeValue && barcodeValue.trim() && !validationService.validateBarcodeInput(barcodeValue).isValid"
        autocomplete="off"
        autocorrect="off"
        autocapitalize="off"
        spellcheck="false"
        @keyup.enter="handleScan"
        @input="handleInput"
      />
      <div id="input-help" class="form-text">
        Enter a valid user ID or scan a barcode
      </div>
      <div v-if="loading" id="loading-status" class="form-text text-primary">
        <i class="bi bi-hourglass-split me-1"></i>
        Processing...
      </div>
    </div>

    <!-- User Info Display -->
    <div v-if="currentUser" class="user-info">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h6 class="mb-1">{{ currentUser.alias_name }}</h6>
          <small class="opacity-75">{{ currentUser.name }} ({{ currentUser.userid }})</small>
        </div>
        <button
          class="btn btn-outline-light btn-sm"
          @click="clearUser"
          :disabled="loading"
          :aria-label="`Clear user ${currentUser.alias_name}`"
          title="Clear user"
        >
          <i class="bi bi-x" aria-hidden="true"></i>
        </button>
      </div>
    </div>

    <!-- Action Buttons -->
    <div v-if="currentUser" class="mt-3">
      <div class="row g-2">
        <div class="col-12">
          <button
            class="btn btn-primary btn-action w-100"
            @click="handleRecordEntry(currentUser.suggestedType || 'Regular')"
            :disabled="loading"
            :aria-label="`Record ${currentUser.suggestedType || 'Regular'} entry for ${currentUser.alias_name}`"
          >
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
            Record {{ currentUser.suggestedType || 'Regular' }} Entry
          </button>
        </div>
        <div v-if="currentUser.suggestedType === 'Regular'" class="col-12">
          <button
            class="btn btn-warning btn-action w-100"
            @click="handleRecordEntry('Overtime')"
            :disabled="loading"
          >
            <i class="bi bi-plus-circle me-1"></i>
            Record Overtime Entry
          </button>
        </div>
        <div v-if="currentUser.suggestedType === 'Overtime'" class="col-12">
          <button
            class="btn btn-success btn-action w-100"
            @click="handleRecordEntry('Regular')"
            :disabled="loading"
          >
            <i class="bi bi-plus-circle me-1"></i>
            Record Regular Entry
          </button>
        </div>
      </div>
    </div>

    <!-- Instructions -->
    <div v-else class="text-muted small mt-3">
      <i class="bi bi-info-circle me-1"></i>
      Scan a barcode or enter a user ID to begin
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from 'vue'
import { validationService } from '../services/validationService'
import { errorService } from '../services/errorService'

interface User {
  id: number
  userid: string
  name: string
  alias_name: string
  email: string
}

interface Props {
  currentUser: User | null
  loading: boolean
}

interface Emits {
  (e: 'user-scanned', userid: string): void
  (e: 'record-entry', type: string): void
  (e: 'clear-user'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const barcodeValue = ref('')
const barcodeInput = ref<HTMLInputElement>()

let debounceTimer: NodeJS.Timeout | null = null

const handleInput = () => {
  if (debounceTimer) {
    clearTimeout(debounceTimer)
  }

  debounceTimer = setTimeout(() => {
    if (barcodeValue.value.trim()) {
      handleScan()
    }
  }, 500)
}

const handleScan = () => {
  const userid = barcodeValue.value.trim()

  if (!userid || props.loading) {
    return
  }

  // Validate barcode input
  const validation = validationService.validateBarcodeInput(userid)
  if (!validation.isValid) {
    errorService.logWarning(`Invalid barcode input: ${validation.errors.join(', ')}`, {
      component: 'Scanner',
      action: 'handleScan',
      additionalData: { userid }
    })

    // Show user-friendly error message
    ;(window as any).Toast?.show(`Invalid barcode: ${validation.errors.join(', ')}`, 'error')
    barcodeValue.value = ''
    return
  }

  // Use sanitized data
  const sanitizedUserid = validation.sanitizedData
  emit('user-scanned', sanitizedUserid)
  barcodeValue.value = ''
}

const handleRecordEntry = (type: string) => {
  emit('record-entry', type)
}

const clearUser = () => {
  emit('clear-user')
  barcodeValue.value = ''
  nextTick(() => {
    barcodeInput.value?.focus()
  })
}

// Focus input on mount
nextTick(() => {
  barcodeInput.value?.focus()
})
</script>
