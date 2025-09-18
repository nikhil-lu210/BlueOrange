// IndexedDB storage utility for offline attendance data

const DB_NAME = import.meta.env.VITE_DB_NAME || 'offline_attendance_db'
const DB_VERSION = 1

const STORES = {
  ATTENDANCES: 'attendances',
  USERS: 'users',
  SYNC_STATUS: 'syncStatus'
}

class Storage {
  private db: IDBDatabase | null = null

  async init(): Promise<void> {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION)

      request.onerror = () => {
        reject(new Error('Failed to open database'))
      }

      request.onsuccess = () => {
        this.db = request.result
        resolve()
      }

      request.onupgradeneeded = (event) => {
        const db = (event.target as IDBOpenDBRequest).result

        // Create attendances store
        if (!db.objectStoreNames.contains(STORES.ATTENDANCES)) {
          const attendanceStore = db.createObjectStore(STORES.ATTENDANCES, { keyPath: 'id', autoIncrement: true })
          attendanceStore.createIndex('userid', 'userid', { unique: false })
          attendanceStore.createIndex('timestamp', 'timestamp', { unique: false })
          attendanceStore.createIndex('synced', 'synced', { unique: false })
        }

        // Create users store
        if (!db.objectStoreNames.contains(STORES.USERS)) {
          const userStore = db.createObjectStore(STORES.USERS, { keyPath: 'userid' })
          userStore.createIndex('name', 'name', { unique: false })
        }

        // Create sync status store
        if (!db.objectStoreNames.contains(STORES.SYNC_STATUS)) {
          db.createObjectStore(STORES.SYNC_STATUS, { keyPath: 'key' })
        }
      }
    })
  }

  private async ensureDB(): Promise<IDBDatabase> {
    if (!this.db) {
      await this.init()
    }
    return this.db!
  }

  // Attendance methods
  async saveAttendance(attendance: any): Promise<any> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.ATTENDANCES], 'readwrite')
      const store = transaction.objectStore(STORES.ATTENDANCES)

      const request = store.add(attendance)

      request.onsuccess = () => {
        resolve({ ...attendance, id: request.result })
      }

      request.onerror = () => {
        reject(new Error('Failed to save attendance'))
      }
    })
  }

  async getAttendances(): Promise<any[]> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.ATTENDANCES], 'readonly')
      const store = transaction.objectStore(STORES.ATTENDANCES)

      const request = store.getAll()

      request.onsuccess = () => {
        resolve(request.result || [])
      }

      request.onerror = () => {
        reject(new Error('Failed to get attendances'))
      }
    })
  }

  async markAsSynced(id: number): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.ATTENDANCES], 'readwrite')
      const store = transaction.objectStore(STORES.ATTENDANCES)

      const getRequest = store.get(id)

      getRequest.onsuccess = () => {
        const attendance = getRequest.result
        if (attendance) {
          attendance.synced = true
          const putRequest = store.put(attendance)

          putRequest.onsuccess = () => resolve()
          putRequest.onerror = () => reject(new Error('Failed to mark as synced'))
        } else {
          reject(new Error('Attendance not found'))
        }
      }

      getRequest.onerror = () => {
        reject(new Error('Failed to get attendance'))
      }
    })
  }

  async deleteAttendance(id: number): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.ATTENDANCES], 'readwrite')
      const store = transaction.objectStore(STORES.ATTENDANCES)

      const request = store.delete(id)

      request.onsuccess = () => resolve()
      request.onerror = () => reject(new Error('Failed to delete attendance'))
    })
  }

  async clearAttendances(): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.ATTENDANCES], 'readwrite')
      const store = transaction.objectStore(STORES.ATTENDANCES)

      const request = store.clear()

      request.onsuccess = () => resolve()
      request.onerror = () => reject(new Error('Failed to clear attendances'))
    })
  }

  // User methods
  async saveUser(user: any): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.USERS], 'readwrite')
      const store = transaction.objectStore(STORES.USERS)

      const request = store.put(user)

      request.onsuccess = () => resolve()
      request.onerror = () => reject(new Error('Failed to save user'))
    })
  }

  async getUsers(): Promise<any[]> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.USERS], 'readonly')
      const store = transaction.objectStore(STORES.USERS)

      const request = store.getAll()

      request.onsuccess = () => {
        resolve(request.result || [])
      }

      request.onerror = () => {
        reject(new Error('Failed to get users'))
      }
    })
  }

  async clearUsers(): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.USERS], 'readwrite')
      const store = transaction.objectStore(STORES.USERS)

      const request = store.clear()

      request.onsuccess = () => resolve()
      request.onerror = () => reject(new Error('Failed to clear users'))
    })
  }

  // Sync status methods
  async setSyncStatus(key: string, value: any): Promise<void> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.SYNC_STATUS], 'readwrite')
      const store = transaction.objectStore(STORES.SYNC_STATUS)

      const request = store.put({ key, value })

      request.onsuccess = () => resolve()
      request.onerror = () => reject(new Error('Failed to set sync status'))
    })
  }

  async getSyncStatus(key: string): Promise<any> {
    const db = await this.ensureDB()
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([STORES.SYNC_STATUS], 'readonly')
      const store = transaction.objectStore(STORES.SYNC_STATUS)

      const request = store.get(key)

      request.onsuccess = () => {
        resolve(request.result?.value || null)
      }

      request.onerror = () => {
        reject(new Error('Failed to get sync status'))
      }
    })
  }
}

export const storage = new Storage()
export { Storage }
