// Mock database service for when SQL.js fails to load
// This provides a simple in-memory storage alternative

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
  employee_shift_id: number
  clock_in_date: string
  clock_in: string
  clock_out?: string
  total_time?: string
  total_adjusted_time?: string
  type: 'Regular' | 'Overtime'
  clockin_medium: 'Manual' | 'QR-Code' | 'Barcode'
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
}

class MockDatabaseService {
  private users: User[] = []
  private attendances: Attendance[] = []
  private nextUserId = 1
  private nextAttendanceId = 1

  async init(): Promise<void> {
    console.log('Mock database initialized (SQL.js not available)')
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
      alias_name: userData.name || ''
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

  async deleteUser(id: number): Promise<void> {
    const userIndex = this.users.findIndex(u => u.id === id)
    if (userIndex !== -1) {
      this.users[userIndex].status = 'Inactive'
      this.users[userIndex].updated_at = new Date().toISOString()
    }
  }

  async insertEmployee(userId: number, aliasName: string): Promise<number> {
    const user = this.users.find(u => u.id === userId)
    if (user) {
      user.alias_name = aliasName
    }
    return userId
  }

  async getEmployeeByUserId(userId: number): Promise<any> {
    const user = this.users.find(u => u.id === userId)
    return user ? { id: userId, user_id: userId, alias_name: user.alias_name } : null
  }

  async insertEmployeeShift(userId: number, startTime: string, endTime: string): Promise<number> {
    return 1 // Mock shift ID
  }

  async getActiveEmployeeShift(userId: number): Promise<any> {
    return {
      id: 1,
      user_id: userId,
      start_time: '09:00:00',
      end_time: '17:00:00',
      status: 'Active'
    }
  }

  async clockIn(userId: number, type: 'Regular' | 'Overtime' = 'Regular', clockinMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode', scannerId?: number): Promise<number> {
    const now = new Date()
    const attendance: Attendance = {
      id: this.nextAttendanceId++,
      user_id: userId,
      employee_shift_id: 1,
      clock_in_date: now.toISOString().split('T')[0],
      clock_in: now.toISOString(),
      type,
      clockin_medium: clockinMedium,
      clockin_scanner_id: scannerId,
      synced: false,
      created_at: now.toISOString(),
      updated_at: now.toISOString()
    }
    this.attendances.push(attendance)
    return attendance.id
  }

  async clockOut(userId: number, clockoutMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode', scannerId?: number): Promise<boolean> {
    const openAttendance = this.attendances.find(a => a.user_id === userId && !a.clock_out)
    if (!openAttendance) {
      throw new Error('No open attendance found for clock out')
    }

    const now = new Date()
    const clockIn = new Date(openAttendance.clock_in)
    const totalSeconds = Math.floor((now.getTime() - clockIn.getTime()) / 1000)
    const totalTime = this.secondsToTimeFormat(totalSeconds)

    openAttendance.clock_out = now.toISOString()
    openAttendance.clockout_medium = clockoutMedium
    openAttendance.clockout_scanner_id = scannerId
    openAttendance.total_time = totalTime
    openAttendance.total_adjusted_time = totalTime
    openAttendance.updated_at = now.toISOString()

    return true
  }

  async getOpenAttendance(userId: number): Promise<Attendance | null> {
    return this.attendances.find(a => a.user_id === userId && !a.clock_out) || null
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
    }).sort((a, b) => new Date(b.clock_in).getTime() - new Date(a.clock_in).getTime())
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
      this.attendances[index].deleted_at = new Date().toISOString()
      this.attendances[index].updated_at = new Date().toISOString()
    }
  }

  async clearAllAttendances(): Promise<void> {
    const now = new Date().toISOString()
    this.attendances.forEach(attendance => {
      if (!attendance.deleted_at) {
        attendance.deleted_at = now
        attendance.updated_at = now
      }
    })
  }

  async executeQuery(sql: string, params: any[] = []): Promise<any[]> {
    // Mock implementation - return empty array
    return []
  }

  private secondsToTimeFormat(totalSeconds: number): string {
    const hours = Math.floor(totalSeconds / 3600)
    const minutes = Math.floor((totalSeconds % 3600) / 60)
    const seconds = totalSeconds % 60
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
  }
}

export const mockDbService = new MockDatabaseService()
export default mockDbService
