import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { dbService, type User } from '@/services/db'

export const useUsersStore = defineStore('users', () => {
  // State
  const users = ref<User[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Getters
  const totalUsers = computed(() => users.value.length)
  const userCount = computed(() => users.value.length)

  // Actions
  const loadUsers = async () => {
    try {
      loading.value = true
      error.value = null
      users.value = await dbService.getAllUsers()
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to load users'
      console.error('Error loading users:', err)
    } finally {
      loading.value = false
    }
  }

  const addUser = async (name: string, email?: string) => {
    try {
      loading.value = true
      error.value = null

      const userId = await dbService.insertUser(name, email)
      const newUser = await dbService.getUserById(userId)

      if (newUser) {
        users.value.unshift(newUser) // Add to beginning of array
      }

      return newUser
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to add user'
      console.error('Error adding user:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateUser = async (id: number, name: string, email?: string) => {
    try {
      loading.value = true
      error.value = null

      await dbService.updateUser(id, name, email)

      // Update the user in the local state
      const userIndex = users.value.findIndex(u => u.id === id)
      if (userIndex !== -1) {
        users.value[userIndex] = {
          ...users.value[userIndex],
          name,
          email
        }
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to update user'
      console.error('Error updating user:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteUser = async (id: number) => {
    try {
      loading.value = true
      error.value = null

      await dbService.deleteUser(id)

      // Remove the user from local state
      users.value = users.value.filter(u => u.id !== id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to delete user'
      console.error('Error deleting user:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getUserById = (id: number): User | undefined => {
    return users.value.find(u => u.id === id)
  }

  const clearError = () => {
    error.value = null
  }

  return {
    // State
    users,
    loading,
    error,
    // Getters
    totalUsers,
    userCount,
    // Actions
    loadUsers,
    addUser,
    updateUser,
    deleteUser,
    getUserById,
    clearError
  }
})
