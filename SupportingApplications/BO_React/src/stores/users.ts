import { create } from 'zustand'
import { userService } from '../services/userService'
import { api } from '../utils/api'

export interface User {
  id: number
  userid: string
  name: string
  alias_name: string
  email?: string
}

interface UsersState {
  users: User[]
  loading: boolean
  error: string | null
  totalUsers: number
  userCount: number
  loadUsers: () => Promise<void>
  addUser: (data: { userid: string; name: string; email?: string; alias_name?: string }) => Promise<User | null>
  updateUser: (id: number, data: Partial<User> & { alias_name?: string; userid?: string }) => Promise<void>
  deleteUser: (id: number) => Promise<void>
  getUserById: (id: number) => User | undefined
  getUser: (userid: string) => User | null
  fetchUser: (userid: string) => Promise<User | null>
  downloadAllUsers: () => Promise<User[]>
  clearUsers: () => Promise<void>
  clearError: () => void
}

export const useUsersStore = create<UsersState>((set, get) => ({
  users: [],
  loading: false,
  error: null,
  totalUsers: 0,
  userCount: 0,

  loadUsers: async () => {
    set({ loading: true, error: null })
    try {
      const users = await userService.getAllUsers()
      set({ users, totalUsers: users.length, userCount: users.length })
    } catch (err: any) {
      set({ error: err?.message || 'Failed to load users' })
    } finally {
      set({ loading: false })
    }
  },

  addUser: async (data) => {
    set({ loading: true, error: null })
    try {
      const id = await userService.saveUser(data as any)
      const newUser = await userService.getUserById(id)
      if (newUser) {
        set(state => ({ users: [newUser as any, ...state.users], totalUsers: state.totalUsers + 1, userCount: state.userCount + 1 }))
        return newUser as any
      }
      return null
    } catch (err: any) {
      set({ error: err?.message || 'Failed to add user' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  updateUser: async (id, data) => {
    set({ loading: true, error: null })
    try {
      // For now, we'll reload all users after update
      await get().loadUsers()
    } catch (err: any) {
      set({ error: err?.message || 'Failed to update user' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  deleteUser: async (id) => {
    set({ loading: true, error: null })
    try {
      // For now, we'll reload all users after delete
      await get().loadUsers()
    } catch (err: any) {
      set({ error: err?.message || 'Failed to delete user' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  getUserById: (id) => {
    const { users } = get()
    return users.find(u => u.id === id)
  },

  getUser: (userid: string) => {
    const { users } = get()
    return users.find(u => u.userid === userid) || null
  },

  fetchUser: async (userid: string) => {
    set({ loading: true, error: null })
    try {
      const user = await api.getUser(userid)
      if (user) {
        await userService.saveUser(user)
        await get().loadUsers()
        return user as User
      }
      return null
    } catch (err: any) {
      set({ error: err?.message || 'Failed to fetch user' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  downloadAllUsers: async () => {
    set({ loading: true, error: null })
    try {
      const allUsers = await userService.syncUsersFromAPI()
      await get().loadUsers()
      return allUsers as User[]
    } catch (err: any) {
      set({ error: err?.message || 'Failed to download users' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  clearUsers: async () => {
    set({ loading: true, error: null })
    try {
      await userService.clearAllUsers()
      await get().loadUsers()
    } catch (err: any) {
      set({ error: err?.message || 'Failed to clear users' })
      throw err
    } finally {
      set({ loading: false })
    }
  },

  clearError: () => set({ error: null })
}))
