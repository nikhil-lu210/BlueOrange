import { ref } from 'vue'
import { defineStore } from 'pinia'
import { smartDbService as dbService } from '@/services/smartDb'
import { api } from '@/utils/api'

export const useUserStore = defineStore('user', () => {
  // State
  const users = ref<any[]>([])

  // Actions
  const loadUsers = async () => {
    try {
      users.value = await dbService.getAllUsers()
    } catch (error) {
      console.error('Failed to load users:', error)
      throw error
    }
  }

  const getUser = (userid: string) => {
    return users.value.find((u: any) => u.userid === userid) || null
  }

  const fetchUser = async (userid: string) => {
    try {
      const user = await api.getUser(userid)
      if (user) {
        await dbService.saveUser(user)
        await loadUsers() // Reload to get updated list
      }
      return user
    } catch (error) {
      console.error('Failed to fetch user:', error)
      throw error
    }
  }

  const downloadAllUsers = async () => {
    try {
      const allUsers = await api.getAllUsers()
      if (allUsers && allUsers.length > 0) {
        for (const user of allUsers) {
          await dbService.saveUser(user)
        }
        await loadUsers() // Reload to get updated list
      }
      return allUsers
    } catch (error) {
      console.error('Failed to download users:', error)
      throw error
    }
  }

  const clearUsers = async () => {
    try {
      await dbService.clearAllUsers()
      await loadUsers()
    } catch (error) {
      console.error('Failed to clear users:', error)
      throw error
    }
  }

  return {
    // State
    users,
    // Actions
    loadUsers,
    getUser,
    fetchUser,
    downloadAllUsers,
    clearUsers
  }
})
