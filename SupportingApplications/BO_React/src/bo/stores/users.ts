import { create } from 'zustand'
import { smartDbService as dbService } from '../services/smartDb'

export interface User {
  id: number
  userid: string
  name: string
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
      const users = await dbService.getAllUsers()
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
      const id = await dbService.insertUser(data)
      const newUser = await dbService.getUserById(id)
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
      await dbService.updateUser(id, data)
      set(state => ({
        users: state.users.map(u => (u.id === id ? { ...u, ...data } : u))
      }))
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
      await dbService.deleteUser(id)
      set(state => ({ users: state.users.filter(u => u.id !== id), totalUsers: Math.max(0, state.totalUsers - 1), userCount: Math.max(0, state.userCount - 1) }))
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

  clearError: () => set({ error: null })
}))
