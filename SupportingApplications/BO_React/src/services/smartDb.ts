// Smart database service for React that uses simple localStorage with fallback
import { simpleStorageService } from './simpleStorage'
import { mockDbService } from './mockDb'

class SmartDatabaseService {
  private useMock = false
  private initialized = false

  async init(): Promise<void> {
    if (this.initialized) return

    try {
      await simpleStorageService.init()
      this.useMock = false
      console.log('Using simple localStorage database')
    } catch (error) {
      console.warn('Simple storage initialization failed, using mock database:', error)
      await mockDbService.init()
      this.useMock = true
    }

    this.initialized = true
  }

  private getService() {
    return this.useMock ? mockDbService : simpleStorageService
  }

  // User operations
  async insertUser(userData: any): Promise<number> {
    await this.init()
    return this.getService().insertUser(userData)
  }

  async getAllUsers(): Promise<any[]> {
    await this.init()
    return this.getService().getAllUsers()
  }

  async getUserById(id: number): Promise<any> {
    await this.init()
    return this.getService().getUserById(id)
  }

  async getUserByUserid(userid: string): Promise<any> {
    await this.init()
    return this.getService().getUserByUserid(userid)
  }

  async updateUser(id: number, userData: any): Promise<void> {
    await this.init()
    return this.getService().updateUser(id, userData)
  }

  async saveUser(userData: any): Promise<number> {
    await this.init()
    return this.getService().saveUser(userData)
  }

  async deleteUser(id: number): Promise<void> {
    await this.init()
    return this.getService().deleteUser(id)
  }

  async clearAllUsers(): Promise<void> {
    await this.init()
    return this.getService().clearAllUsers()
  }

  // Attendance operations
  async recordEntry(userId: number, type: 'Regular' | 'Overtime' = 'Regular'): Promise<number> {
    await this.init()
    return this.getService().recordEntry(userId, type)
  }

  async getUserEntries(userId: number): Promise<any[]> {
    await this.init()
    return this.getService().getUserEntries(userId)
  }

  async getAllAttendances(): Promise<any[]> {
    await this.init()
    return this.getService().getAllAttendances()
  }

  async getUnsyncedAttendances(): Promise<any[]> {
    await this.init()
    return this.getService().getUnsyncedAttendances()
  }

  async markAttendanceAsSynced(id: number): Promise<void> {
    await this.init()
    return this.getService().markAttendanceAsSynced(id)
  }

  async deleteAttendance(id: number): Promise<void> {
    await this.init()
    return this.getService().deleteAttendance(id)
  }

  async clearAllAttendances(): Promise<void> {
    await this.init()
    return this.getService().clearAllAttendances()
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    await this.init()
    return this.getService().getStats()
  }

  async clearAllData(): Promise<void> {
    await this.init()
    return this.getService().clearAllData()
  }

  async executeQuery(sql: string, params: any[] = []): Promise<any[]> {
    await this.init()
    return this.getService().executeQuery(sql, params)
  }
}

export const smartDbService = new SmartDatabaseService()
export default smartDbService
