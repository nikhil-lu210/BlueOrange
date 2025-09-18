// API utility for communicating with Laravel backend
import { getServerName } from './constants'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://blueorange.test/api'
const API_TIMEOUT = import.meta.env.VITE_API_TIMEOUT || 60000
const API_ENDPOINTS = {
  getUser: '/offline-attendance/user',
  getUserStatus: '/offline-attendance/user',
  getAllUsers: '/offline-attendance/users',
  syncAttendances: '/offline-attendance/sync'
}

class API {
  private baseURL: string

  constructor(baseURL: string = API_BASE_URL) {
    this.baseURL = baseURL
  }

  private async makeRequest(endpoint: string, options: RequestInit = {}): Promise<any> {
    const url = `${this.baseURL}${endpoint}`
    console.log(`🌐 Making API request to: ${url}`)

    const defaultOptions: RequestInit = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
      },
      ...options
    }

    try {
      // Add timeout using AbortController
      const controller = new AbortController()
      const timeoutId = setTimeout(() => {
        console.log(`⏰ Request timeout after ${API_TIMEOUT}ms for: ${url}`)
        controller.abort()
      }, API_TIMEOUT)

      const response = await fetch(url, {
        ...defaultOptions,
        signal: controller.signal
      })

      clearTimeout(timeoutId)

      if (!response.ok) {
        const errorText = await response.text()
        console.error(`❌ HTTP error! status: ${response.status}, response: ${errorText}`)
        throw new Error(`HTTP error! status: ${response.status} - ${errorText}`)
      }

      const data = await response.json()
      console.log(`✅ API request successful: ${url}`)
      return data
    } catch (error) {
      if (error.name === 'AbortError') {
        const timeoutError = new Error(`Request timeout after ${API_TIMEOUT}ms`)
        console.error(`⏰ ${timeoutError.message} for: ${url}`)
        throw timeoutError
      }
      if (error.name === 'TypeError' && error.message.includes('fetch')) {
        const connectionError = new Error(`Cannot connect to ${getServerName()} server. Please check if the server is running at ${this.baseURL}`)
        console.error(`🔌 ${connectionError.message}`)
        throw connectionError
      }
      console.error('❌ API request failed:', error)
      throw error
    }
  }

  async getUser(userid: string): Promise<any> {
    try {
      const response = await this.makeRequest(`${API_ENDPOINTS.getUser}/${userid}`)

      if (response.success && response.data) {
        return response.data
      } else {
        throw new Error(response.message || 'User not found')
      }
    } catch (error) {
      console.error('Failed to get user:', error)
      throw error
    }
  }

  async getUserAttendanceStatus(userid: string): Promise<any> {
    try {
      const response = await this.makeRequest(`${API_ENDPOINTS.getUser}/${userid}/status`)

      if (response.success && response.data) {
        return response.data
      } else {
        throw new Error(response.message || 'Failed to get user status')
      }
    } catch (error) {
      console.error('Failed to get user attendance status:', error)
      throw error
    }
  }

  async getAllUsers(): Promise<any[]> {
    try {
      const response = await this.makeRequest(API_ENDPOINTS.getAllUsers)

      if (response.success && response.data) {
        return response.data
      } else {
        throw new Error(response.message || 'Failed to get users')
      }
    } catch (error) {
      console.error('Failed to get all users:', error)
      throw error
    }
  }

  async syncAttendances(attendances: any[]): Promise<any> {
    try {
      const response = await this.makeRequest(API_ENDPOINTS.syncAttendances, {
        method: 'POST',
        body: JSON.stringify({
          attendances: attendances.map(att => ({
            user_id: att.user_id,
            entry_date_time: att.entry_date_time,
            type: att.type
          }))
        })
      })

      if (response.success) {
        return {
          success: true,
          syncedCount: response.data?.synced_count || attendances.length,
          totalCount: response.data?.total_count || attendances.length,
          errors: response.data?.errors || [],
          message: response.message
        }
      } else {
        throw new Error(response.message || 'Sync failed')
      }
    } catch (error) {
      console.error('Failed to sync attendances:', error)
      throw error
    }
  }

  async testConnection(): Promise<boolean> {
    try {
      console.log(`🔍 Testing connection to ${getServerName()} server...`)
      await this.makeRequest('/offline-attendance/users', { method: 'GET' })
      console.log('✅ Connection test successful')
      return true
    } catch (error) {
      console.log('❌ Connection test failed:', error.message)
      return false
    }
  }
}

export const api = new API()
export { API }
