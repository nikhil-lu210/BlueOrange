// Mock database service for React fallback

export interface User {
  id: number
  userid: string
  name: string
  first_name?: string
  last_name?: string
  email?: string
  status: 'Active' | 'Inactive'
  created_at: string
  updated_at: string
  alias_name: string
}

export interface Attendance {
  id: number
  user_id: number
  employee_shift_id?: number
  clock_in_date?: string
  clock_in?: string
  clock_out?: string
  total_time?: string
  total_adjusted_time?: string
  type: 'Regular' | 'Overtime'
  clockin_medium?: 'Manual' | 'QR-Code' | 'Barcode'
  clockout_medium?: 'Manual' | 'QR-Code' | 'Barcode'
  clockin_scanner_id?: number
  clockout_scanner_id?: number
  ip_address?: string
  country?: string
  city?: string
  zip_code?: string
  time_zone?: string
  latitude?: number
  longitude?: number
  synced: boolean
  created_at: string
  updated_at: string
  user_name?: string
  userid?: string
  alias_name?: string
  entry_date_time?: string
}

class MockDatabaseService {
  private users: User[] = []
  private attendances: Attendance[] = []
  private nextUserId = 1
  private nextAttendanceId = 1

  async init(): Promise<void> {
    console.log('Mock database initialized (fallback)')
  }

  async insertUser(userData: Partial<User>): Promise<number> {
    const user: User = {
      id: this.nextUserId++,
      userid: userData.userid || '',
      name: userData.name || '',
      first_name: userData.first_name,
      last_name: userData.last_name,
      email: userData.email,
      status: userData.status || 'Active',
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
      alias_name: userData.alias_name || userData.name || ''
    }
    this.users.push(user)
    return user.id
  }

  async getAllUsers(): Promise<User[]> {
    return [...this.users].filter(u => u.status === 'Active')
  }

  async getUserById(id: number): Promise<User | null> {
    return this.users.find(u => u.id === id) || null
  }

  async getUserByUserid(userid: string): Promise<User | null> {
    return this.users.find(u => u.userid === userid && u.status === 'Active') || null
  }

  async updateUser(id: number, userData: Partial<User>): Promise<void> {
    const userIndex = this.users.findIndex(u => u.id === id)
    if (userIndex !== -1) {
      this.users[userIndex] = {
        ...this.users[userIndex],
        ...userData,
        updated_at: new Date().toISOString()
      }
    }
  }

  async saveUser(userData: User): Promise<number> {
    const existingIndex = this.users.findIndex(u => u.id === userData.id || u.userid === userData.userid)
    
    if (existingIndex !== -1) {
      this.users[existingIndex] = {
        ...this.users[existingIndex],
        ...userData,
        updated_at: new Date().toISOString()
      }
      return this.users[existingIndex].id
    } else {
      return await this.insertUser(userData)
    }
  }

  async deleteUser(id: number): Promise<void> {
    const userIndex = this.users.findIndex(u => u.id === id)
    if (userIndex !== -1) {
      this.users[userIndex].status = 'Inactive'
      this.users[userIndex].updated_at = new Date().toISOString()
    }
  }

  async recordEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<number> {
    const now = new Date()
    const attendance: Attendance = {
      id: this.nextAttendanceId++,
      user_id: userId,
      entry_date_time: now.toISOString(),
      type,
      synced: false,
      created_at: now.toISOString(),
      updated_at: now.toISOString()
    }
    this.attendances.push(attendance)
    return attendance.id
  }

  async getUserEntries(userId: number): Promise<Attendance[]> {
    return this.attendances.filter(a => a.user_id === userId)
  }

  async getAllAttendances(): Promise<Attendance[]> {
    return this.attendances.map(attendance => {
      const user = this.users.find(u => u.id === attendance.user_id)
      return {
        ...attendance,
        user_name: user?.name || 'Unknown',
        userid: user?.userid || '',
        alias_name: user?.alias_name || user?.name || 'Unknown'
      }
    }).sort((a, b) => new Date(b.entry_date_time || b.clock_in || '').getTime() - new Date(a.entry_date_time || a.clock_in || '').getTime())
  }

  async getUnsyncedAttendances(): Promise<Attendance[]> {
    return this.attendances.filter(a => !a.synced).map(attendance => {
      const user = this.users.find(u => u.id === attendance.user_id)
      return {
        ...attendance,
        user_name: user?.name || 'Unknown',
        userid: user?.userid || '',
        alias_name: user?.alias_name || user?.name || 'Unknown'
      }
    })
  }

  async markAttendanceAsSynced(id: number): Promise<void> {
    const attendance = this.attendances.find(a => a.id === id)
    if (attendance) {
      attendance.synced = true
      attendance.updated_at = new Date().toISOString()
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

  async clearAllUsers(): Promise<void> {
    this.users = []
  }

  async clearAllData(): Promise<void> {
    this.users = []
    this.attendances = []
  }

  async executeQuery(sql: string, params: any[] = []): Promise<any[]> {
    return []
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    const unsynced = this.attendances.filter(a => !a.synced)
    return {
      totalUsers: this.users.filter(u => u.status === 'Active').length,
      totalAttendances: this.attendances.length,
      unsyncedAttendances: unsynced.length
    }
  }
}

export const mockDbService = new MockDatabaseService()
export default mockDbService
