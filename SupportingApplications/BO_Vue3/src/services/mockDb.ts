// Simplified mock database service for when localStorage fails
// This provides a simple in-memory storage alternative

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

class MockDatabaseService {
  private users: User[] = []
  private attendances: Attendance[] = []
  private nextUserId = 1
  private nextAttendanceId = 1

  async init(): Promise<void> {
    console.log('Mock database initialized (localStorage not available)')
  }

  // User operations
  async insertUser(userData: Partial<User>): Promise<number> {
    const user: User = {
      id: this.nextUserId++,
      userid: userData.userid || '',
      name: userData.name || '',
      alias_name: userData.alias_name || userData.name || ''
    }
    this.users.push(user)
    return user.id
  }

  async getAllUsers(): Promise<User[]> {
    return [...this.users]
  }

  async getUserById(id: number): Promise<User | null> {
    return this.users.find(u => u.id === id) || null
  }

  async getUserByUserid(userid: string): Promise<User | null> {
    return this.users.find(u => u.userid === userid) || null
  }

  async updateUser(id: number, userData: Partial<User>): Promise<void> {
    const userIndex = this.users.findIndex(u => u.id === id)
    if (userIndex !== -1) {
      this.users[userIndex] = { ...this.users[userIndex], ...userData }
    }
  }

  async saveUser(userData: User): Promise<number> {
    const existingIndex = this.users.findIndex(u => u.id === userData.id || u.userid === userData.userid)

    if (existingIndex !== -1) {
      this.users[existingIndex] = { ...this.users[existingIndex], ...userData }
      return this.users[existingIndex].id
    } else {
      return await this.insertUser(userData)
    }
  }

  async deleteUser(id: number): Promise<void> {
    const userIndex = this.users.findIndex(u => u.id === id)
    if (userIndex !== -1) {
      this.users.splice(userIndex, 1)
    }
  }

  async clearAllUsers(): Promise<void> {
    this.users = []
  }

  // Attendance operations - simplified to just record entries
  async recordEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<number> {
    const now = new Date()
    const attendance: Attendance = {
      id: this.nextAttendanceId++,
      user_id: userId,
      entry_date_time: now.toISOString(),
      type,
      synced: false,
      created_at: now.toISOString()
    }
    this.attendances.push(attendance)
    return attendance.id
  }

  async getUserEntries(userId: number): Promise<Attendance[]> {
    return this.attendances.filter(a => a.user_id === userId)
  }

  async getAllAttendances(): Promise<Attendance[]> {
    return [...this.attendances].sort((a, b) =>
      new Date(b.entry_date_time).getTime() - new Date(a.entry_date_time).getTime()
    )
  }

  async getUnsyncedAttendances(): Promise<Attendance[]> {
    return this.attendances.filter(a => !a.synced)
  }

  async markAttendanceAsSynced(id: number): Promise<void> {
    const attendance = this.attendances.find(a => a.id === id)
    if (attendance) {
      attendance.synced = true
    }
  }

  async deleteAttendance(id: number): Promise<void> {
    const index = this.attendances.findIndex(a => a.id === id)
    if (index !== -1) {
      this.attendances.splice(index, 1)
    }
  }

  async clearAllAttendances(): Promise<void> {
    this.attendances = []
  }

  async clearAllData(): Promise<void> {
    this.users = []
    this.attendances = []
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    const unsynced = this.attendances.filter(a => !a.synced)
    return {
      totalUsers: this.users.length,
      totalAttendances: this.attendances.length,
      unsyncedAttendances: unsynced.length
    }
  }

  async executeQuery(sql: string, params: any[] = []): Promise<any[]> {
    // Mock implementation - return empty array
    return []
  }
}

export const mockDbService = new MockDatabaseService()
export default mockDbService
