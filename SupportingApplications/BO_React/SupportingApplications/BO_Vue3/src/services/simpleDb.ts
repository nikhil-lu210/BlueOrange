// Simple database service using IndexedDB directly (no SQLite)
// This avoids the memory issues with sql.js

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
  alias_name?: string
}

export interface Attendance {
  id: number
  user_id: number
  userid: string
  user_name: string
  alias_name: string
  clock_in: string
  clock_out?: string
  total_time?: string
  type: 'Regular' | 'Overtime'
  clockin_medium: 'Manual' | 'QR-Code' | 'Barcode'
  clockout_medium?: 'Manual' | 'QR-Code' | 'Barcode'
  synced: boolean
  created_at: string
  updated_at: string
  deleted_at?: string
}

class SimpleDatabaseService {
  private dbName = import.meta.env.VITE_DB_NAME || 'blueorange_offline'
  private dbVersion = 1
  private initialized = false
  private db: IDBDatabase | null = null

  async init(): Promise<void> {
    if (this.initialized) return

    try {
      console.log('Initializing simple database with IndexedDB...')

      this.db = await this.openDatabase()
      this.initialized = true
      console.log('✅ Simple database initialized successfully')
    } catch (error) {
      console.error('❌ Failed to initialize simple database:', error)
      throw error
    }
  }

  private openDatabase(): Promise<IDBDatabase> {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion)

      request.onerror = () => reject(request.error)
      request.onsuccess = () => resolve(request.result)

      request.onupgradeneeded = () => {
        const db = request.result

        // Create users store
        if (!db.objectStoreNames.contains('users')) {
          const userStore = db.createObjectStore('users', { keyPath: 'id', autoIncrement: true })
          userStore.createIndex('userid', 'userid', { unique: true })
          userStore.createIndex('status', 'status')
        }

        // Create attendances store
        if (!db.objectStoreNames.contains('attendances')) {
          const attendanceStore = db.createObjectStore('attendances', { keyPath: 'id', autoIncrement: true })
          attendanceStore.createIndex('user_id', 'user_id')
          attendanceStore.createIndex('userid', 'userid')
          attendanceStore.createIndex('synced', 'synced')
          attendanceStore.createIndex('deleted_at', 'deleted_at')
          attendanceStore.createIndex('clock_in', 'clock_in')
        }
      }
    })
  }

  private async ensureDB(): Promise<IDBDatabase> {
    if (!this.initialized) {
      await this.init()
    }
    if (!this.db) {
      throw new Error('Database not initialized')
    }
    return this.db
  }

  // User operations
  async insertUser(userData: Partial<User>): Promise<number> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['users'], 'readwrite')
      const store = transaction.objectStore('users')

      const user: Omit<User, 'id'> = {
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

      return new Promise((resolve, reject) => {
        const request = store.add(user)
        request.onsuccess = () => resolve(request.result as number)
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to insert user:', error)
      throw error
    }
  }

  async getAllUsers(): Promise<User[]> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['users'], 'readonly')
      const store = transaction.objectStore('users')

      return new Promise((resolve, reject) => {
        const request = store.getAll()
        request.onsuccess = () => {
          const users = request.result.filter((user: User) => user.status === 'Active')
          resolve(users)
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get users:', error)
      throw error
    }
  }

  async getUserByUserid(userid: string): Promise<User | null> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['users'], 'readonly')
      const store = transaction.objectStore('users')
      const index = store.index('userid')

      return new Promise((resolve, reject) => {
        const request = index.get(userid)
        request.onsuccess = () => {
          const user = request.result
          if (user && user.status === 'Active') {
            resolve(user)
          } else {
            resolve(null)
          }
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get user by userid:', error)
      throw error
    }
  }

  async getUserById(id: number): Promise<User | null> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['users'], 'readonly')
      const store = transaction.objectStore('users')

      return new Promise((resolve, reject) => {
        const request = store.get(id)
        request.onsuccess = () => {
          const user = request.result
          if (user && user.status === 'Active') {
            resolve(user)
          } else {
            resolve(null)
          }
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get user by ID:', error)
      throw error
    }
  }

  async updateUser(id: number, userData: Partial<User>): Promise<void> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['users'], 'readwrite')
      const store = transaction.objectStore('users')

      return new Promise((resolve, reject) => {
        const getRequest = store.get(id)
        getRequest.onsuccess = () => {
          const user = getRequest.result
          if (user) {
            const updatedUser = {
              ...user,
              ...userData,
              updated_at: new Date().toISOString()
            }

            const putRequest = store.put(updatedUser)
            putRequest.onsuccess = () => resolve()
            putRequest.onerror = () => reject(putRequest.error)
          } else {
            reject(new Error('User not found'))
          }
        }
        getRequest.onerror = () => reject(getRequest.error)
      })
    } catch (error) {
      console.error('Failed to update user:', error)
      throw error
    }
  }

  // Attendance operations
  async clockIn(userId: number, type: 'Regular' | 'Overtime' = 'Regular', clockinMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode'): Promise<number> {
    try {
      const user = await this.getUserById(userId)
      if (!user) {
        throw new Error('User not found')
      }

      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readwrite')
      const store = transaction.objectStore('attendances')

      const attendance: Omit<Attendance, 'id'> = {
        user_id: userId,
        userid: user.userid,
        user_name: user.name,
        alias_name: user.alias_name || user.name,
        clock_in: new Date().toISOString(),
        type,
        clockin_medium: clockinMedium,
        synced: false,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }

      return new Promise((resolve, reject) => {
        const request = store.add(attendance)
        request.onsuccess = () => resolve(request.result as number)
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to clock in:', error)
      throw error
    }
  }

  async clockOut(userId: number, clockoutMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode'): Promise<boolean> {
    try {
      const openAttendance = await this.getOpenAttendance(userId)
      if (!openAttendance) {
        throw new Error('No open attendance found for clock out')
      }

      const clockOutTime = new Date()
      const clockInTime = new Date(openAttendance.clock_in)
      const totalSeconds = Math.floor((clockOutTime.getTime() - clockInTime.getTime()) / 1000)
      const totalTime = this.secondsToTimeFormat(totalSeconds)

      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readwrite')
      const store = transaction.objectStore('attendances')

      return new Promise((resolve, reject) => {
        const getRequest = store.get(openAttendance.id)
        getRequest.onsuccess = () => {
          const attendance = getRequest.result
          if (attendance) {
            const updatedAttendance = {
              ...attendance,
              clock_out: clockOutTime.toISOString(),
              clockout_medium: clockoutMedium,
              total_time: totalTime,
              updated_at: new Date().toISOString()
            }

            const putRequest = store.put(updatedAttendance)
            putRequest.onsuccess = () => resolve(true)
            putRequest.onerror = () => reject(putRequest.error)
          } else {
            reject(new Error('Attendance not found'))
          }
        }
        getRequest.onerror = () => reject(getRequest.error)
      })
    } catch (error) {
      console.error('Failed to clock out:', error)
      throw error
    }
  }

  async getOpenAttendance(userId: number): Promise<Attendance | null> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readonly')
      const store = transaction.objectStore('attendances')
      const index = store.index('user_id')

      return new Promise((resolve, reject) => {
        const request = index.getAll(userId)
        request.onsuccess = () => {
          const attendances = request.result
          const openAttendance = attendances.find((att: Attendance) =>
            !att.clock_out && !att.deleted_at
          )
          resolve(openAttendance || null)
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get open attendance:', error)
      throw error
    }
  }

  async getAllAttendances(): Promise<Attendance[]> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readonly')
      const store = transaction.objectStore('attendances')

      return new Promise((resolve, reject) => {
        const request = store.getAll()
        request.onsuccess = () => {
          const attendances = request.result.filter((att: Attendance) => !att.deleted_at)
          // Sort by clock_in descending
          attendances.sort((a, b) => new Date(b.clock_in).getTime() - new Date(a.clock_in).getTime())
          resolve(attendances)
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get all attendances:', error)
      throw error
    }
  }

  async getUnsyncedAttendances(): Promise<Attendance[]> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readonly')
      const store = transaction.objectStore('attendances')
      const index = store.index('synced')

      return new Promise((resolve, reject) => {
        const request = index.getAll(false)
        request.onsuccess = () => {
          const attendances = request.result.filter((att: Attendance) => !att.deleted_at)
          resolve(attendances)
        }
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to get unsynced attendances:', error)
      throw error
    }
  }

  async markAttendanceAsSynced(id: number): Promise<void> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readwrite')
      const store = transaction.objectStore('attendances')

      return new Promise((resolve, reject) => {
        const getRequest = store.get(id)
        getRequest.onsuccess = () => {
          const attendance = getRequest.result
          if (attendance) {
            const updatedAttendance = {
              ...attendance,
              synced: true,
              updated_at: new Date().toISOString()
            }

            const putRequest = store.put(updatedAttendance)
            putRequest.onsuccess = () => resolve()
            putRequest.onerror = () => reject(putRequest.error)
          } else {
            reject(new Error('Attendance not found'))
          }
        }
        getRequest.onerror = () => reject(getRequest.error)
      })
    } catch (error) {
      console.error('Failed to mark attendance as synced:', error)
      throw error
    }
  }

  async deleteAttendance(id: number): Promise<void> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readwrite')
      const store = transaction.objectStore('attendances')

      return new Promise((resolve, reject) => {
        const getRequest = store.get(id)
        getRequest.onsuccess = () => {
          const attendance = getRequest.result
          if (attendance) {
            const updatedAttendance = {
              ...attendance,
              deleted_at: new Date().toISOString(),
              updated_at: new Date().toISOString()
            }

            const putRequest = store.put(updatedAttendance)
            putRequest.onsuccess = () => resolve()
            putRequest.onerror = () => reject(putRequest.error)
          } else {
            reject(new Error('Attendance not found'))
          }
        }
        getRequest.onerror = () => reject(getRequest.error)
      })
    } catch (error) {
      console.error('Failed to delete attendance:', error)
      throw error
    }
  }

  async clearAllAttendances(): Promise<void> {
    try {
      const db = await this.ensureDB()
      const transaction = db.transaction(['attendances'], 'readwrite')
      const store = transaction.objectStore('attendances')

      return new Promise((resolve, reject) => {
        const request = store.clear()
        request.onsuccess = () => resolve()
        request.onerror = () => reject(request.error)
      })
    } catch (error) {
      console.error('Failed to clear all attendances:', error)
      throw error
    }
  }

  async clearAllData(): Promise<void> {
    try {
      const db = await this.ensureDB()

      // Clear users
      const userTransaction = db.transaction(['users'], 'readwrite')
      const userStore = userTransaction.objectStore('users')
      await new Promise<void>((resolve, reject) => {
        const request = userStore.clear()
        request.onsuccess = () => resolve()
        request.onerror = () => reject(request.error)
      })

      // Clear attendances
      const attendanceTransaction = db.transaction(['attendances'], 'readwrite')
      const attendanceStore = attendanceTransaction.objectStore('attendances')
      await new Promise<void>((resolve, reject) => {
        const request = attendanceStore.clear()
        request.onsuccess = () => resolve()
        request.onerror = () => reject(request.error)
      })

      console.log('✅ All data cleared')
    } catch (error) {
      console.error('Failed to clear all data:', error)
      throw error
    }
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    try {
      const [users, attendances, unsyncedAttendances] = await Promise.all([
        this.getAllUsers(),
        this.getAllAttendances(),
        this.getUnsyncedAttendances()
      ])

      return {
        totalUsers: users.length,
        totalAttendances: attendances.length,
        unsyncedAttendances: unsyncedAttendances.length
      }
    } catch (error) {
      console.error('Failed to get stats:', error)
      throw error
    }
  }

  private secondsToTimeFormat(totalSeconds: number): string {
    const hours = Math.floor(totalSeconds / 3600)
    const minutes = Math.floor((totalSeconds % 3600) / 60)
    const seconds = totalSeconds % 60
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
  }
}

export const simpleDbService = new SimpleDatabaseService()
export default simpleDbService
