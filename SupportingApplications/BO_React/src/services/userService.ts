// Direct user service - no confusing chains
import { api } from '../utils/api'
import { DB_NAME } from '../utils/constants'

const USERS_KEY = `${DB_NAME}_users`

export interface User {
  id: number
  userid: string
  name: string
  alias_name: string
  email?: string
}

class UserService {
  private initialized = false

  async init(): Promise<void> {
    if (this.initialized) return

    // Initialize localStorage key if it doesn't exist
    if (!localStorage.getItem(USERS_KEY)) {
      localStorage.setItem(USERS_KEY, JSON.stringify([]))
    }

    this.initialized = true
  }

  // Get all users from localStorage
  async getAllUsers(): Promise<User[]> {
    await this.init()
    const data = localStorage.getItem(USERS_KEY)
    return data ? JSON.parse(data) : []
  }

  // Get user by ID
  async getUserById(id: number): Promise<User | null> {
    const users = await this.getAllUsers()
    return users.find(u => u.id === id) || null
  }

  // Get user by userid
  async getUserByUserid(userid: string): Promise<User | null> {
    const users = await this.getAllUsers()
    return users.find(u => u.userid === userid) || null
  }

  // Save user to localStorage (preserves API ID)
  async saveUser(userData: User): Promise<number> {
    await this.init()
    console.log('💾 UserService.saveUser called with:', userData)

    const users = await this.getAllUsers()

    // Find existing user by userid (not by ID, since ID might be different)
    const existingIndex = users.findIndex(u => u.userid === userData.userid)

    if (existingIndex !== -1) {
      // Update existing user - FORCE the API ID to be used
      console.log('🔄 Updating existing user:', users[existingIndex].name, 'Old ID:', users[existingIndex].id, 'New ID:', userData.id)
      users[existingIndex] = {
        ...users[existingIndex],
        ...userData,
        id: userData.id  // CRITICAL: Force the API ID
      }
    } else {
      // Add new user
      console.log('➕ Adding new user:', userData.name, 'ID:', userData.id)
      users.push(userData)
    }

    // Save to localStorage
    localStorage.setItem(USERS_KEY, JSON.stringify(users))
    console.log('✅ UserService: Saved users to localStorage, total count:', users.length)

    return userData.id
  }

  // Sync users from API
  async syncUsersFromAPI(): Promise<User[]> {
    console.log('🌐 UserService: Syncing users from API...')

    try {
      // Clear existing users
      await this.clearAllUsers()

      // Get users from API
      const apiUsers = await api.getAllUsers()
      console.log('📊 UserService: Received users from API:', apiUsers.length)

      // Save each user to localStorage
      for (const user of apiUsers) {
        await this.saveUser(user)
      }

      console.log('✅ UserService: Sync completed, saved', apiUsers.length, 'users')
      return apiUsers
    } catch (error) {
      console.error('❌ UserService: Sync failed:', error)
      throw error
    }
  }

  // Clear all users
  async clearAllUsers(): Promise<void> {
    await this.init()
    localStorage.setItem(USERS_KEY, JSON.stringify([]))
    console.log('🗑️ UserService: Cleared all users')
  }

  // Get user count
  async getUserCount(): Promise<number> {
    const users = await this.getAllUsers()
    return users.length
  }
}

export const userService = new UserService()
export default userService
