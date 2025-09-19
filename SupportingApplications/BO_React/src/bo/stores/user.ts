import { create } from 'zustand'
import { smartDbService as dbService } from '../services/smartDb'
import { api } from '../utils/api'

interface UserState {
  users: any[]
  loadUsers: () => Promise<void>
  getUser: (userid: string) => any | null
  fetchUser: (userid: string) => Promise<any>
  downloadAllUsers: () => Promise<any[]>
  clearUsers: () => Promise<void>
}

export const useUserStore = create<UserState>((set, get) => ({
  users: [],

  loadUsers: async () => {
    const users = await dbService.getAllUsers()
    set({ users })
  },

  getUser: (userid: string) => {
    const users = get().users
    return users.find((u: any) => u.userid === userid) || null
  },

  fetchUser: async (userid: string) => {
    const user = await api.getUser(userid)
    if (user) {
      await dbService.saveUser(user)
      await get().loadUsers()
    }
    return user
  },

  downloadAllUsers: async () => {
    const allUsers = await api.getAllUsers()
    if (allUsers && allUsers.length > 0) {
      for (const user of allUsers) {
        await dbService.saveUser(user)
      }
      await get().loadUsers()
    }
    return allUsers
  },

  clearUsers: async () => {
    await dbService.clearAllUsers()
    await get().loadUsers()
  }
}))
