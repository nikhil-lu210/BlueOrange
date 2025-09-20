// Simple storage service - only essential data (React)
import { DB_NAME } from '../utils/constants'

export interface User {
  id: number
  userid: string
  name: string
  alias_name: string
}

export interface Attendance {
  id: number
  user_id: number
  entry_date_time: string  // Single datetime field for when the entry was made
  type: 'Regular' | 'Overtime'
  synced: boolean
  created_at: string
}

class SimpleStorageService {
  private initialized = false

  async init(): Promise<void> {
    if (this.initialized) return
    // localStorage is available in Electron renderer and browser
    this.initialized = true
  }

  private async ensureInit(): Promise<void> {
    if (!this.initialized) {
      await this.init()
    }
  }

  // User operations
  async insertUser(userData: Partial<User>): Promise<number> {
    await this.ensureInit()

    const users = this.getUsers()
    const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1

    const user: User = {
      id: newId,
      userid: userData.userid || '',
      name: userData.name || '',
      alias_name: userData.alias_name || userData.name || ''
    }

    users.push(user)
    this.saveUsers(users)

    return newId
  }

  async getAllUsers(): Promise<User[]> {
    await this.ensureInit()
    return this.getUsers()
  }

  async getUserByUserid(userid: string): Promise<User | null> {
    await this.ensureInit()
    const users = this.getUsers()
    return users.find(u => u.userid === userid) || null
  }

  async getUserById(id: number): Promise<User | null> {
    await this.ensureInit()
    const users = this.getUsers()
    return users.find(u => u.id === id) || null
  }

  async updateUser(id: number, userData: Partial<User>): Promise<void> {
    await this.ensureInit()
    const users = this.getUsers()
    const index = users.findIndex(u => u.id === id)

    if (index !== -1) {
      users[index] = { ...users[index], ...userData }
      this.saveUsers(users)
    }
  }

  async saveUser(userData: User): Promise<number> {
    await this.ensureInit()
    const users = this.getUsers()
    const existingIndex = users.findIndex(u => u.id === userData.id || u.userid === userData.userid)

    if (existingIndex !== -1) {
      users[existingIndex] = { ...users[existingIndex], ...userData }
      this.saveUsers(users)
      return users[existingIndex].id
    } else {
      return await this.insertUser(userData)
    }
  }

  async clearAllUsers(): Promise<void> {
    await this.ensureInit()
    this.saveUsers([])
  }

  async deleteUser(id: number): Promise<void> {
    await this.ensureInit()
    const users = this.getUsers()
    const filtered = users.filter(u => u.id !== id)
    this.saveUsers(filtered)
  }

  async executeQuery(_sql: string, _params: any[] = []): Promise<any[]> {
    await this.ensureInit()
    // Not supported in simple storage; return empty array for compatibility
    return []
  }

  // Attendance operations - simplified to just record entries
  async recordEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<number> {
    await this.ensureInit()

    const attendances = this.getAttendances()
    const newId = attendances.length > 0 ? Math.max(...attendances.map(a => a.id)) + 1 : 1

    const now = new Date()
    const attendance: Attendance = {
      id: newId,
      user_id: userId,
      entry_date_time: now.toISOString(),
      type,
      synced: false,
      created_at: now.toISOString()
    }

    attendances.push(attendance)
    this.saveAttendances(attendances)

    return newId
  }

  // Get all entries for a user (for display purposes)
  async getUserEntries(userId: number): Promise<Attendance[]> {
    await this.ensureInit()
    const attendances = this.getAttendances()
    return attendances.filter(a => a.user_id === userId)
  }

  async getAllAttendances(): Promise<Attendance[]> {
    await this.ensureInit()
    return this.getAttendances().sort((a, b) =>
      new Date(b.entry_date_time).getTime() - new Date(a.entry_date_time).getTime()
    )
  }

  async getUnsyncedAttendances(): Promise<Attendance[]> {
    await this.ensureInit()
    return this.getAttendances().filter(a => !a.synced)
  }

         async markAttendanceAsSynced(id: number): Promise<void> {
           await this.ensureInit()
           const attendances = this.getAttendances()
           const attendance = attendances.find(a => a.id === id)

           if (attendance) {
             attendance.synced = true
             this.saveAttendances(attendances)
           }
         }

  async deleteAttendance(id: number): Promise<void> {
    await this.ensureInit()
    const attendances = this.getAttendances()
    const filtered = attendances.filter(a => a.id !== id)
    this.saveAttendances(filtered)
  }

  async clearAllAttendances(): Promise<void> {
    await this.ensureInit()
    this.saveAttendances([])
  }

  async clearAllData(): Promise<void> {
    await this.ensureInit()
    this.saveUsers([])
    this.saveAttendances([])
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    await this.ensureInit()
    const users = this.getUsers()
    const attendances = this.getAttendances()
    const unsynced = attendances.filter(a => !a.synced)

    return {
      totalUsers: users.length,
      totalAttendances: attendances.length,
      unsyncedAttendances: unsynced.length
    }
  }

  // Private helper methods
  private getUsers(): User[] {
    try {
      const data = localStorage.getItem(`${DB_NAME}_users`)
      return data ? JSON.parse(data) : []
    } catch {
      return []
    }
  }

  private saveUsers(users: User[]): void {
    try {
      localStorage.setItem(`${DB_NAME}_users`, JSON.stringify(users))
    } catch (error) {
      console.error('Failed to save users:', error)
    }
  }

  private getAttendances(): Attendance[] {
    try {
      const key = `${DB_NAME}_attendances`;
      const data = localStorage.getItem(key)
      const attendances = data ? JSON.parse(data) : []
      return attendances
    } catch (error) {
      console.error('Error retrieving attendances from localStorage:', error);
      return []
    }
  }

  private saveAttendances(attendances: Attendance[]): void {
    try {
      const key = `${DB_NAME}_attendances`;
      localStorage.setItem(key, JSON.stringify(attendances))
    } catch (error) {
      console.error('Failed to save attendances:', error)
    }
  }
}

export const simpleStorageService = new SimpleStorageService()
export default simpleStorageService
