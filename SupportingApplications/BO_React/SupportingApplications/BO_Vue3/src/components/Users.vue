<template>
  <div class="users-panel p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="mb-0">
        <i class="bi bi-people me-2"></i>
        User Management
      </h5>
      <button
        class="btn btn-primary btn-sm"
        @click="showAddUserModal = true"
        :disabled="loading"
      >
        <i class="bi bi-plus-circle me-1"></i>
        Add User
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-4">
      <div class="loading-spinner"></div>
      <p class="text-muted mt-2">Loading users...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="alert alert-danger">
      <i class="bi bi-exclamation-triangle me-2"></i>
      {{ error }}
      <button class="btn btn-sm btn-outline-danger ms-2" @click="loadUsers">
        <i class="bi bi-arrow-clockwise"></i>
        Retry
      </button>
    </div>

    <!-- Users List -->
    <div v-else-if="users.length === 0" class="text-center text-muted py-4">
      <i class="bi bi-person-x display-4 d-block mb-2"></i>
      <p class="mb-0">No users found</p>
      <small>Add a user to get started</small>
    </div>

    <div v-else class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Alias</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>
              <code>{{ user.userid }}</code>
            </td>
            <td>
              <div>
                <div class="fw-medium">{{ user.name }}</div>
                <small class="text-muted" v-if="user.first_name || user.last_name">
                  {{ user.first_name }} {{ user.last_name }}
                </small>
              </div>
            </td>
            <td>
              <span class="badge bg-info">{{ user.alias_name }}</span>
            </td>
            <td>{{ user.email || '-' }}</td>
            <td>
              <span class="badge" :class="user.status === 'Active' ? 'bg-success' : 'bg-secondary'">
                {{ user.status }}
              </span>
            </td>
            <td>
              <small>{{ formatDate(user.created_at) }}</small>
            </td>
            <td>
              <div class="btn-group btn-group-sm">
                <button
                  class="btn btn-outline-primary"
                  @click="editUser(user)"
                  :disabled="loading"
                  title="Edit user"
                >
                  <i class="bi bi-pencil"></i>
                </button>
                <button
                  class="btn btn-outline-danger"
                  @click="deleteUser(user.id)"
                  :disabled="loading"
                  title="Delete user"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit User Modal -->
    <div v-if="showAddUserModal || editingUser" class="modal show d-block" style="background-color: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-person-plus me-2"></i>
              {{ editingUser ? 'Edit User' : 'Add New User' }}
            </h5>
            <button
              type="button"
              class="btn-close"
              @click="closeModal"
              :disabled="loading"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveUser">
              <div class="mb-3">
                <label for="userid" class="form-label">User ID *</label>
                <input
                  id="userid"
                  v-model="userForm.userid"
                  type="text"
                  class="form-control"
                  placeholder="Enter unique user ID"
                  required
                  :disabled="loading"
                />
              </div>

              <div class="mb-3">
                <label for="name" class="form-label">Full Name *</label>
                <input
                  id="name"
                  v-model="userForm.name"
                  type="text"
                  class="form-control"
                  placeholder="Enter full name"
                  required
                  :disabled="loading"
                />
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input
                      id="first_name"
                      v-model="userForm.first_name"
                      type="text"
                      class="form-control"
                      placeholder="First name"
                      :disabled="loading"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input
                      id="last_name"
                      v-model="userForm.last_name"
                      type="text"
                      class="form-control"
                      placeholder="Last name"
                      :disabled="loading"
                    />
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  id="email"
                  v-model="userForm.email"
                  type="email"
                  class="form-control"
                  placeholder="user@example.com"
                  :disabled="loading"
                />
              </div>

              <div class="mb-3">
                <label for="alias_name" class="form-label">Alias Name *</label>
                <input
                  id="alias_name"
                  v-model="userForm.alias_name"
                  type="text"
                  class="form-control"
                  placeholder="Display name"
                  required
                  :disabled="loading"
                />
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select
                  id="status"
                  v-model="userForm.status"
                  class="form-select"
                  :disabled="loading"
                >
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="closeModal"
              :disabled="loading"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="saveUser"
              :disabled="loading || !isFormValid"
            >
              <span v-if="loading" class="loading-spinner me-2"></span>
              <i v-else class="bi bi-check-circle me-1"></i>
              {{ editingUser ? 'Update' : 'Add' }} User
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useUsersStore } from '@/stores/users'
import { smartDbService as dbService } from '@/services/smartDb'
import { type User } from '@/services/db'

// Store
const usersStore = useUsersStore()

// Reactive state
const loading = ref(false)
const showAddUserModal = ref(false)
const editingUser = ref<User | null>(null)

// Form data
const userForm = reactive({
  userid: '',
  name: '',
  first_name: '',
  last_name: '',
  email: '',
  alias_name: '',
  status: 'Active' as 'Active' | 'Inactive'
})

// Computed
const users = computed(() => usersStore.users)
const error = computed(() => usersStore.error)
const isFormValid = computed(() => {
  return userForm.userid.trim() &&
         userForm.name.trim() &&
         userForm.alias_name.trim()
})

// Methods
const loadUsers = async () => {
  try {
    loading.value = true
    await usersStore.loadUsers()
  } catch (err) {
    console.error('Failed to load users:', err)
  } finally {
    loading.value = false
  }
}

const addUser = async () => {
  try {
    loading.value = true

    // First create the user
    const userId = await dbService.insertUser({
      userid: userForm.userid,
      name: userForm.name,
      first_name: userForm.first_name || undefined,
      last_name: userForm.last_name || undefined,
      email: userForm.email || undefined,
      status: userForm.status
    })

    // Then create the employee record for alias_name
    await dbService.insertEmployee(userId, userForm.alias_name)

    // Reload users
    await usersStore.loadUsers()

    closeModal()
    resetForm()

    // Show success message
    ;(window as any).Toast?.show('User added successfully', 'success')
  } catch (err) {
    console.error('Failed to add user:', err)
    ;(window as any).Toast?.show('Failed to add user', 'error')
  } finally {
    loading.value = false
  }
}

const editUser = (user: User) => {
  editingUser.value = user
  userForm.userid = user.userid
  userForm.name = user.name
  userForm.first_name = user.first_name || ''
  userForm.last_name = user.last_name || ''
  userForm.email = user.email || ''
  userForm.alias_name = user.alias_name
  userForm.status = user.status
  showAddUserModal.value = true
}

const updateUser = async () => {
  if (!editingUser.value) return

  try {
    loading.value = true

    // Update user
    await dbService.updateUser(editingUser.value.id, {
      userid: userForm.userid,
      name: userForm.name,
      first_name: userForm.first_name || undefined,
      last_name: userForm.last_name || undefined,
      email: userForm.email || undefined,
      status: userForm.status
    })

    // Update employee alias_name
    const employee = await dbService.getEmployeeByUserId(editingUser.value.id)
    if (employee) {
      // Update existing employee record
      await dbService.executeQuery(
        'UPDATE employees SET alias_name = ?, updated_at = ? WHERE user_id = ?',
        [userForm.alias_name, new Date().toISOString(), editingUser.value.id]
      )
    } else {
      // Create new employee record
      await dbService.insertEmployee(editingUser.value.id, userForm.alias_name)
    }

    // Reload users
    await usersStore.loadUsers()

    closeModal()
    resetForm()

    // Show success message
    ;(window as any).Toast?.show('User updated successfully', 'success')
  } catch (err) {
    console.error('Failed to update user:', err)
    ;(window as any).Toast?.show('Failed to update user', 'error')
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id: number) => {
  if (!confirm('Are you sure you want to delete this user?')) return

  try {
    loading.value = true
    await usersStore.deleteUser(id)
    ;(window as any).Toast?.show('User deleted successfully', 'success')
  } catch (err) {
    console.error('Failed to delete user:', err)
    ;(window as any).Toast?.show('Failed to delete user', 'error')
  } finally {
    loading.value = false
  }
}

const saveUser = () => {
  if (editingUser.value) {
    updateUser()
  } else {
    addUser()
  }
}

const closeModal = () => {
  showAddUserModal.value = false
  editingUser.value = null
  resetForm()
}

const resetForm = () => {
  userForm.userid = ''
  userForm.name = ''
  userForm.first_name = ''
  userForm.last_name = ''
  userForm.email = ''
  userForm.alias_name = ''
  userForm.status = 'Active'
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}

// Initialize
onMounted(() => {
  loadUsers()
})
</script>

<style scoped>
.users-panel {
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border: 1px solid #e2e8f0;
}

.loading-spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 2px solid #f3f3f3;
  border-top: 2px solid #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.modal {
  z-index: 1055;
}

.table th {
  background-color: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
  font-weight: 600;
  color: #1e293b;
}

.table td {
  vertical-align: middle;
  border-bottom: 1px solid #e2e8f0;
}

.btn-group-sm .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}
</style>
