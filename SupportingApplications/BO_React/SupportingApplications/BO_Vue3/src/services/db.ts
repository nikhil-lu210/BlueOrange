import initSqlJs from 'sql.js'
import { mockDbService } from './mockDb'

// Types for our database operations - matching Laravel structure
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
}

export interface Employee {
  id: number
  user_id: number
  alias_name: string
  created_at: string
  updated_at: string
}

export interface EmployeeShift {
  id: number
  user_id: number
  start_time: string
  end_time: string
  status: 'Active' | 'Inactive'
  created_at: string
  updated_at: string
}

export interface Attendance {
  id: number
  user_id: number
  employee_shift_id: number
  clock_in_date: string
  clock_in: string
  clock_out?: string
  total_time?: string // HH:MM:SS format
  total_adjusted_time?: string // HH:MM:SS format
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
}

export interface DailyBreak {
  id: number
  attendance_id: number
  break_in_at: string
  break_out_at?: string
  total_time?: string // HH:MM:SS format
  over_break?: string // HH:MM:SS format
  created_at: string
  updated_at: string
}

class DatabaseService {
  private db: any = null
  private SQL: any = null

  async init(): Promise<void> {
    try {
      // Initialize SQL.js with proper WASM loading
      this.SQL = await initSqlJs({
        // Use CDN for WASM file to avoid MIME type issues
        locateFile: (file: string) => {
          if (file.endsWith('.wasm')) {
            return `https://sql.js.org/dist/${file}`
          }
          return file
        }
      })

      // Create a new database in memory
      this.db = new this.SQL.Database()

      // Create tables
      await this.createTables()

      console.log('SQLite database initialized successfully')
    } catch (error) {
      console.error('Failed to initialize SQLite database:', error)
      // Fallback: try without WASM file location
      try {
        console.log('Trying fallback initialization...')
        this.SQL = await initSqlJs()
        this.db = new this.SQL.Database()
        await this.createTables()
        console.log('SQLite database initialized with fallback method')
      } catch (fallbackError) {
        console.error('Fallback initialization also failed:', fallbackError)
        console.log('Using mock database service as fallback')
        // Use mock service as final fallback
        return mockDbService.init()
      }
    }
  }

  private async ensureDB(): Promise<any> {
    if (!this.db) {
      await this.init()
    }
    if (!this.db) {
      throw new Error('Database not initialized')
    }
    return this.db
  }

  private async createTables(): Promise<void> {
    // Create users table - matching Laravel structure
    const createUsersTable = `
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        userid TEXT UNIQUE NOT NULL,
        name TEXT NOT NULL,
        first_name TEXT,
        last_name TEXT,
        email TEXT,
        status TEXT DEFAULT 'Active' CHECK (status IN ('Active', 'Inactive')),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `

    // Create employees table - for alias_name
    const createEmployeesTable = `
      CREATE TABLE IF NOT EXISTS employees (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        alias_name TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
      )
    `

    // Create employee_shifts table
    const createEmployeeShiftsTable = `
      CREATE TABLE IF NOT EXISTS employee_shifts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        start_time TEXT NOT NULL,
        end_time TEXT NOT NULL,
        status TEXT DEFAULT 'Active' CHECK (status IN ('Active', 'Inactive')),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
      )
    `

    // Create attendances table - matching Laravel migration exactly
    const createAttendancesTable = `
      CREATE TABLE IF NOT EXISTS attendances (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        employee_shift_id INTEGER NOT NULL,
        clock_in_date TEXT NOT NULL,
        clock_in TEXT NOT NULL,
        clock_out TEXT,
        total_time TEXT,
        total_adjusted_time TEXT,
        type TEXT DEFAULT 'Regular' CHECK (type IN ('Regular', 'Overtime')),
        clockin_medium TEXT DEFAULT 'Manual' CHECK (clockin_medium IN ('Manual', 'QR-Code', 'Barcode')),
        clockout_medium TEXT CHECK (clockout_medium IN ('Manual', 'QR-Code', 'Barcode')),
        clockin_scanner_id INTEGER,
        clockout_scanner_id INTEGER,
        ip_address TEXT,
        country TEXT,
        city TEXT,
        zip_code TEXT,
        time_zone TEXT,
        latitude REAL,
        longitude REAL,
        synced BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        deleted_at DATETIME,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (employee_shift_id) REFERENCES employee_shifts (id) ON DELETE CASCADE,
        FOREIGN KEY (clockin_scanner_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (clockout_scanner_id) REFERENCES users (id) ON DELETE CASCADE,
        UNIQUE(user_id, employee_shift_id, clock_in_date, clock_in, type)
      )
    `

    // Create daily_breaks table
    const createDailyBreaksTable = `
      CREATE TABLE IF NOT EXISTS daily_breaks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        attendance_id INTEGER NOT NULL,
        break_in_at TEXT NOT NULL,
        break_out_at TEXT,
        total_time TEXT,
        over_break TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (attendance_id) REFERENCES attendances (id) ON DELETE CASCADE
      )
    `

    // Create indexes for better performance - matching Laravel patterns
    const createIndexes = `
      CREATE INDEX IF NOT EXISTS idx_users_userid ON users(userid);
      CREATE INDEX IF NOT EXISTS idx_users_status ON users(status);
      CREATE INDEX IF NOT EXISTS idx_employees_user_id ON employees(user_id);
      CREATE INDEX IF NOT EXISTS idx_employee_shifts_user_id ON employee_shifts(user_id);
      CREATE INDEX IF NOT EXISTS idx_employee_shifts_status ON employee_shifts(status);
      CREATE INDEX IF NOT EXISTS idx_attendances_user_id ON attendances(user_id);
      CREATE INDEX IF NOT EXISTS idx_attendances_clock_in_date ON attendances(clock_in_date);
      CREATE INDEX IF NOT EXISTS idx_attendances_clock_in ON attendances(clock_in);
      CREATE INDEX IF NOT EXISTS idx_attendances_type ON attendances(type);
      CREATE INDEX IF NOT EXISTS idx_attendances_synced ON attendances(synced);
      CREATE INDEX IF NOT EXISTS idx_attendances_deleted_at ON attendances(deleted_at);
      CREATE INDEX IF NOT EXISTS idx_daily_breaks_attendance_id ON daily_breaks(attendance_id);
    `

    try {
      this.db.exec(createUsersTable)
      this.db.exec(createEmployeesTable)
      this.db.exec(createEmployeeShiftsTable)
      this.db.exec(createAttendancesTable)
      this.db.exec(createDailyBreaksTable)
      this.db.exec(createIndexes)

      console.log('Database tables created successfully')
    } catch (error) {
      console.error('Failed to create tables:', error)
      throw error
    }
  }

  // User operations - matching Laravel structure
  async insertUser(userData: Partial<User>): Promise<number> {
    try {
      const stmt = this.db.prepare(`
        INSERT INTO users (userid, name, first_name, last_name, email, status)
        VALUES (?, ?, ?, ?, ?, ?)
      `)

      const now = new Date().toISOString()
      stmt.run([
        userData.userid || '',
        userData.name || '',
        userData.first_name || null,
        userData.last_name || null,
        userData.email || null,
        userData.status || 'Active'
      ])
      stmt.free()

      // Get the last inserted ID
      const result = this.db.exec('SELECT last_insert_rowid() as id')
      return result[0].values[0][0] as number
    } catch (error) {
      console.error('Failed to insert user:', error)
      throw error
    }
  }

  async getAllUsers(): Promise<User[]> {
    try {
      const db = await this.ensureDB()
      const result = db.exec(`
        SELECT u.id, u.userid, u.name, u.first_name, u.last_name, u.email, u.status, u.created_at, u.updated_at,
               e.alias_name
        FROM users u
        LEFT JOIN employees e ON u.id = e.user_id
        WHERE u.status = 'Active'
        ORDER BY u.created_at DESC
      `)

      if (result.length === 0) return []

      return result[0].values.map((row: any[]) => ({
        id: row[0],
        userid: row[1],
        name: row[2],
        first_name: row[3],
        last_name: row[4],
        email: row[5],
        status: row[6] as 'Active' | 'Inactive',
        created_at: row[7],
        updated_at: row[8],
        alias_name: row[9] || row[2] // Use alias_name if available, otherwise use name
      }))
    } catch (error) {
      console.error('Failed to get users:', error)
      throw error
    }
  }

  async getUserById(id: number): Promise<User | null> {
    try {
      const stmt = this.db.prepare(`
        SELECT u.id, u.userid, u.name, u.first_name, u.last_name, u.email, u.status, u.created_at, u.updated_at,
               e.alias_name
        FROM users u
        LEFT JOIN employees e ON u.id = e.user_id
        WHERE u.id = ?
      `)

      const result = stmt.getAsObject([id])
      stmt.free()

      if (result.id === undefined) return null

      return {
        id: result.id,
        userid: result.userid,
        name: result.name,
        first_name: result.first_name,
        last_name: result.last_name,
        email: result.email,
        status: result.status as 'Active' | 'Inactive',
        created_at: result.created_at,
        updated_at: result.updated_at,
        alias_name: result.alias_name || result.name
      }
    } catch (error) {
      console.error('Failed to get user by ID:', error)
      throw error
    }
  }

  async getUserByUserid(userid: string): Promise<User | null> {
    try {
      const stmt = this.db.prepare(`
        SELECT u.id, u.userid, u.name, u.first_name, u.last_name, u.email, u.status, u.created_at, u.updated_at,
               e.alias_name
        FROM users u
        LEFT JOIN employees e ON u.id = e.user_id
        WHERE u.userid = ? AND u.status = 'Active'
      `)

      const result = stmt.getAsObject([userid])
      stmt.free()

      if (result.id === undefined) return null

      return {
        id: result.id,
        userid: result.userid,
        name: result.name,
        first_name: result.first_name,
        last_name: result.last_name,
        email: result.email,
        status: result.status as 'Active' | 'Inactive',
        created_at: result.created_at,
        updated_at: result.updated_at,
        alias_name: result.alias_name || result.name
      }
    } catch (error) {
      console.error('Failed to get user by userid:', error)
      throw error
    }
  }

  async updateUser(id: number, userData: Partial<User>): Promise<void> {
    try {
      const stmt = this.db.prepare(`
        UPDATE users
        SET userid = ?, name = ?, first_name = ?, last_name = ?, email = ?, status = ?, updated_at = ?
        WHERE id = ?
      `)

      const now = new Date().toISOString()
      stmt.run([
        userData.userid,
        userData.name,
        userData.first_name || null,
        userData.last_name || null,
        userData.email || null,
        userData.status || 'Active',
        now,
        id
      ])
      stmt.free()
    } catch (error) {
      console.error('Failed to update user:', error)
      throw error
    }
  }

  async deleteUser(id: number): Promise<void> {
    try {
      // Soft delete - set status to Inactive instead of hard delete
      const stmt = this.db.prepare(`
        UPDATE users
        SET status = 'Inactive', updated_at = ?
        WHERE id = ?
      `)

      const now = new Date().toISOString()
      stmt.run([now, id])
      stmt.free()
    } catch (error) {
      console.error('Failed to delete user:', error)
      throw error
    }
  }

  // Employee operations
  async insertEmployee(userId: number, aliasName: string): Promise<number> {
    try {
      const stmt = this.db.prepare(`
        INSERT INTO employees (user_id, alias_name)
        VALUES (?, ?)
      `)

      stmt.run([userId, aliasName])
      stmt.free()

      // Get the last inserted ID
      const result = this.db.exec('SELECT last_insert_rowid() as id')
      return result[0].values[0][0] as number
    } catch (error) {
      console.error('Failed to insert employee:', error)
      throw error
    }
  }

  async getEmployeeByUserId(userId: number): Promise<Employee | null> {
    try {
      const stmt = this.db.prepare(`
        SELECT id, user_id, alias_name, created_at, updated_at
        FROM employees
        WHERE user_id = ?
      `)

      const result = stmt.getAsObject([userId])
      stmt.free()

      if (result.id === undefined) return null

      return {
        id: result.id,
        user_id: result.user_id,
        alias_name: result.alias_name,
        created_at: result.created_at,
        updated_at: result.updated_at
      }
    } catch (error) {
      console.error('Failed to get employee by user ID:', error)
      throw error
    }
  }

  // Employee Shift operations
  async insertEmployeeShift(userId: number, startTime: string, endTime: string): Promise<number> {
    try {
      const stmt = this.db.prepare(`
        INSERT INTO employee_shifts (user_id, start_time, end_time)
        VALUES (?, ?, ?)
      `)

      stmt.run([userId, startTime, endTime])
      stmt.free()

      // Get the last inserted ID
      const result = this.db.exec('SELECT last_insert_rowid() as id')
      return result[0].values[0][0] as number
    } catch (error) {
      console.error('Failed to insert employee shift:', error)
      throw error
    }
  }

  async getActiveEmployeeShift(userId: number): Promise<EmployeeShift | null> {
    try {
      const stmt = this.db.prepare(`
        SELECT id, user_id, start_time, end_time, status, created_at, updated_at
        FROM employee_shifts
        WHERE user_id = ? AND status = 'Active'
        ORDER BY created_at DESC
        LIMIT 1
      `)

      const result = stmt.getAsObject([userId])
      stmt.free()

      if (result.id === undefined) return null

      return {
        id: result.id,
        user_id: result.user_id,
        start_time: result.start_time,
        end_time: result.end_time,
        status: result.status as 'Active' | 'Inactive',
        created_at: result.created_at,
        updated_at: result.updated_at
      }
    } catch (error) {
      console.error('Failed to get active employee shift:', error)
      throw error
    }
  }

  // Attendance operations - matching Laravel business logic
  async clockIn(userId: number, type: 'Regular' | 'Overtime' = 'Regular', clockinMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode', scannerId?: number): Promise<number> {
    try {
      // Get or create employee shift
      let employeeShift = await this.getActiveEmployeeShift(userId)
      if (!employeeShift) {
        // Create default shift (9 AM to 5 PM)
        const shiftId = await this.insertEmployeeShift(userId, '09:00:00', '17:00:00')
        employeeShift = await this.getActiveEmployeeShift(userId)
      }

      if (!employeeShift) {
        throw new Error('Failed to create employee shift')
      }

      const now = new Date()
      const clockInDate = now.toISOString().split('T')[0] // YYYY-MM-DD
      const clockInTime = now.toISOString() // Full datetime

      const stmt = this.db.prepare(`
        INSERT INTO attendances (
          user_id, employee_shift_id, clock_in_date, clock_in, type,
          clockin_medium, clockin_scanner_id, synced
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
      `)

      stmt.run([
        userId,
        employeeShift.id,
        clockInDate,
        clockInTime,
        type,
        clockinMedium,
        scannerId || null,
        0 // Not synced yet
      ])
      stmt.free()

      // Get the last inserted ID
      const result = this.db.exec('SELECT last_insert_rowid() as id')
      return result[0].values[0][0] as number
    } catch (error) {
      console.error('Failed to clock in:', error)
      throw error
    }
  }

  async clockOut(userId: number, clockoutMedium: 'Manual' | 'QR-Code' | 'Barcode' = 'Barcode', scannerId?: number): Promise<boolean> {
    try {
      // Find open attendance (clocked in but not clocked out)
      const openAttendance = await this.getOpenAttendance(userId)
      if (!openAttendance) {
        throw new Error('No open attendance found for clock out')
      }

      const now = new Date()
      const clockOutTime = now.toISOString()

      // Calculate total time
      const clockIn = new Date(openAttendance.clock_in)
      const clockOut = new Date(clockOutTime)
      const totalSeconds = Math.floor((clockOut.getTime() - clockIn.getTime()) / 1000)
      const totalTime = this.secondsToTimeFormat(totalSeconds)

      const stmt = this.db.prepare(`
        UPDATE attendances
        SET clock_out = ?, clockout_medium = ?, clockout_scanner_id = ?,
            total_time = ?, total_adjusted_time = ?, updated_at = ?
        WHERE id = ?
      `)

      stmt.run([
        clockOutTime,
        clockoutMedium,
        scannerId || null,
        totalTime,
        totalTime, // For now, adjusted time = total time
        now.toISOString(),
        openAttendance.id
      ])
      stmt.free()

      return true
    } catch (error) {
      console.error('Failed to clock out:', error)
      throw error
    }
  }

  async getOpenAttendance(userId: number): Promise<Attendance | null> {
    try {
      const stmt = this.db.prepare(`
        SELECT a.id, a.user_id, a.employee_shift_id, a.clock_in_date, a.clock_in, a.clock_out,
               a.total_time, a.total_adjusted_time, a.type, a.clockin_medium, a.clockout_medium,
               a.clockin_scanner_id, a.clockout_scanner_id, a.ip_address, a.country, a.city,
               a.zip_code, a.time_zone, a.latitude, a.longitude, a.synced, a.created_at, a.updated_at
        FROM attendances a
        WHERE a.user_id = ? AND a.clock_out IS NULL
        ORDER BY a.clock_in DESC
        LIMIT 1
      `)

      const result = stmt.getAsObject([userId])
      stmt.free()

      if (result.id === undefined) return null

      return {
        id: result.id,
        user_id: result.user_id,
        employee_shift_id: result.employee_shift_id,
        clock_in_date: result.clock_in_date,
        clock_in: result.clock_in,
        clock_out: result.clock_out,
        total_time: result.total_time,
        total_adjusted_time: result.total_adjusted_time,
        type: result.type as 'Regular' | 'Overtime',
        clockin_medium: result.clockin_medium as 'Manual' | 'QR-Code' | 'Barcode',
        clockout_medium: result.clockout_medium as 'Manual' | 'QR-Code' | 'Barcode' | undefined,
        clockin_scanner_id: result.clockin_scanner_id,
        clockout_scanner_id: result.clockout_scanner_id,
        ip_address: result.ip_address,
        country: result.country,
        city: result.city,
        zip_code: result.zip_code,
        time_zone: result.time_zone,
        latitude: result.latitude,
        longitude: result.longitude,
        synced: Boolean(result.synced),
        created_at: result.created_at,
        updated_at: result.updated_at
      }
    } catch (error) {
      console.error('Failed to get open attendance:', error)
      throw error
    }
  }

  async getAllAttendances(): Promise<Attendance[]> {
    try {
      const result = this.db.exec(`
        SELECT a.id, a.user_id, a.employee_shift_id, a.clock_in_date, a.clock_in, a.clock_out,
               a.total_time, a.total_adjusted_time, a.type, a.clockin_medium, a.clockout_medium,
               a.clockin_scanner_id, a.clockout_scanner_id, a.ip_address, a.country, a.city,
               a.zip_code, a.time_zone, a.latitude, a.longitude, a.synced, a.created_at, a.updated_at,
               u.name as user_name, u.userid, e.alias_name
        FROM attendances a
        JOIN users u ON a.user_id = u.id
        LEFT JOIN employees e ON u.id = e.user_id
        WHERE a.deleted_at IS NULL
        ORDER BY a.clock_in DESC
      `)

      if (result.length === 0) return []

      return result[0].values.map((row: any[]) => ({
        id: row[0],
        user_id: row[1],
        employee_shift_id: row[2],
        clock_in_date: row[3],
        clock_in: row[4],
        clock_out: row[5],
        total_time: row[6],
        total_adjusted_time: row[7],
        type: row[8] as 'Regular' | 'Overtime',
        clockin_medium: row[9] as 'Manual' | 'QR-Code' | 'Barcode',
        clockout_medium: row[10] as 'Manual' | 'QR-Code' | 'Barcode' | undefined,
        clockin_scanner_id: row[11],
        clockout_scanner_id: row[12],
        ip_address: row[13],
        country: row[14],
        city: row[15],
        zip_code: row[16],
        time_zone: row[17],
        latitude: row[18],
        longitude: row[19],
        synced: Boolean(row[20]),
        created_at: row[21],
        updated_at: row[22],
        user_name: row[23],
        userid: row[24],
        alias_name: row[25] || row[23]
      }))
    } catch (error) {
      console.error('Failed to get attendances:', error)
      throw error
    }
  }

  async getUnsyncedAttendances(): Promise<Attendance[]> {
    try {
      const result = this.db.exec(`
        SELECT a.id, a.user_id, a.employee_shift_id, a.clock_in_date, a.clock_in, a.clock_out,
               a.total_time, a.total_adjusted_time, a.type, a.clockin_medium, a.clockout_medium,
               a.clockin_scanner_id, a.clockout_scanner_id, a.ip_address, a.country, a.city,
               a.zip_code, a.time_zone, a.latitude, a.longitude, a.synced, a.created_at, a.updated_at,
               u.name as user_name, u.userid, e.alias_name
        FROM attendances a
        JOIN users u ON a.user_id = u.id
        LEFT JOIN employees e ON u.id = e.user_id
        WHERE a.synced = 0 AND a.deleted_at IS NULL
        ORDER BY a.clock_in DESC
      `)

      if (result.length === 0) return []

      return result[0].values.map((row: any[]) => ({
        id: row[0],
        user_id: row[1],
        employee_shift_id: row[2],
        clock_in_date: row[3],
        clock_in: row[4],
        clock_out: row[5],
        total_time: row[6],
        total_adjusted_time: row[7],
        type: row[8] as 'Regular' | 'Overtime',
        clockin_medium: row[9] as 'Manual' | 'QR-Code' | 'Barcode',
        clockout_medium: row[10] as 'Manual' | 'QR-Code' | 'Barcode' | undefined,
        clockin_scanner_id: row[11],
        clockout_scanner_id: row[12],
        ip_address: row[13],
        country: row[14],
        city: row[15],
        zip_code: row[16],
        time_zone: row[17],
        latitude: row[18],
        longitude: row[19],
        synced: Boolean(row[20]),
        created_at: row[21],
        updated_at: row[22],
        user_name: row[23],
        userid: row[24],
        alias_name: row[25] || row[23]
      }))
    } catch (error) {
      console.error('Failed to get unsynced attendances:', error)
      throw error
    }
  }

  async markAttendanceAsSynced(id: number): Promise<void> {
    try {
      const stmt = this.db.prepare(`
        UPDATE attendances
        SET synced = 1, updated_at = ?
        WHERE id = ?
      `)

      const now = new Date().toISOString()
      stmt.run([now, id])
      stmt.free()
    } catch (error) {
      console.error('Failed to mark attendance as synced:', error)
      throw error
    }
  }

  async deleteAttendance(id: number): Promise<void> {
    try {
      const stmt = this.db.prepare(`
        UPDATE attendances
        SET deleted_at = ?, updated_at = ?
        WHERE id = ?
      `)

      const now = new Date().toISOString()
      stmt.run([now, now, id])
      stmt.free()
    } catch (error) {
      console.error('Failed to delete attendance:', error)
      throw error
    }
  }

  async clearAllAttendances(): Promise<void> {
    try {
      const stmt = this.db.prepare(`
        UPDATE attendances
        SET deleted_at = ?, updated_at = ?
        WHERE deleted_at IS NULL
      `)

      const now = new Date().toISOString()
      stmt.run([now, now])
      stmt.free()
    } catch (error) {
      console.error('Failed to clear all attendances:', error)
      throw error
    }
  }

  // Helper method to convert seconds to HH:MM:SS format
  private secondsToTimeFormat(totalSeconds: number): string {
    const hours = Math.floor(totalSeconds / 3600)
    const minutes = Math.floor((totalSeconds % 3600) / 60)
    const seconds = totalSeconds % 60
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
  }

  // Utility methods
  async executeQuery(sql: string, params: any[] = []): Promise<any[]> {
    try {
      const stmt = this.db.prepare(sql)
      const result = stmt.all(params)
      stmt.free()
      return result
    } catch (error) {
      console.error('Failed to execute query:', error)
      throw error
    }
  }

  async getStats(): Promise<{ totalUsers: number; totalAttendances: number; unsyncedAttendances: number }> {
    try {
      const usersResult = this.db.exec('SELECT COUNT(*) as count FROM users')
      const attendancesResult = this.db.exec('SELECT COUNT(*) as count FROM attendances')
      const unsyncedResult = this.db.exec('SELECT COUNT(*) as count FROM attendances WHERE synced = 0')

      return {
        totalUsers: usersResult[0]?.values[0][0] || 0,
        totalAttendances: attendancesResult[0]?.values[0][0] || 0,
        unsyncedAttendances: unsyncedResult[0]?.values[0][0] || 0
      }
    } catch (error) {
      console.error('Failed to get stats:', error)
      throw error
    }
  }

  // Export database as binary data (for backup)
  exportDatabase(): Uint8Array {
    return this.db.export()
  }

  // Import database from binary data (for restore)
  importDatabase(data: Uint8Array): void {
    this.db = new this.SQL.Database(data)
  }

  // Close the database connection
  close(): void {
    if (this.db) {
      this.db.close()
      this.db = null
    }
  }
}

// Create and export a singleton instance
export const dbService = new DatabaseService()
export default dbService
