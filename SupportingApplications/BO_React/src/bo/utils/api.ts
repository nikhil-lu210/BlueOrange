// API utility for communicating with Laravel backend (React/Vite)
import { getServerName, API_TIMEOUT as DEFAULT_TIMEOUT } from './constants'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://blueorange.test/api'
const API_TIMEOUT = Number(import.meta.env.VITE_API_TIMEOUT || DEFAULT_TIMEOUT)
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
    console.log(`Making API request to: ${url}`)

    const defaultOptions: RequestInit = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(options.headers || {})
      },
      ...options
    }

    try {
      const controller = new AbortController()
      const timeoutId = setTimeout(() => controller.abort(), API_TIMEOUT)

      const response = await fetch(url, {
        ...defaultOptions,
        signal: controller.signal
      })

      clearTimeout(timeoutId)

      if (!response.ok) {
        const errorText = await response.text()
        throw new Error(`HTTP ${response.status}: ${errorText}`)
      }

      return await response.json()
    } catch (error: any) {
      if (error?.name === 'AbortError') {
        throw new Error(`Request timeout after ${API_TIMEOUT}ms`)
      }
      if (error?.name === 'TypeError' && String(error?.message || '').includes('fetch')) {
        throw new Error(`Cannot connect to ${getServerName()} server. Please check if the server is running at ${this.baseURL}`)
      }
      console.error('API request failed:', error)
      throw error
    }
  }

  async getUser(userid: string): Promise<any> {
    const response = await this.makeRequest(`${API_ENDPOINTS.getUser}/${userid}`)
    if (response?.success && response?.data) return response.data
    throw new Error(response?.message || 'User not found')
  }

  async getUserAttendanceStatus(userid: string): Promise<any> {
    const response = await this.makeRequest(`${API_ENDPOINTS.getUserStatus}/${userid}/status`)
    if (response?.success && response?.data) return response.data
    throw new Error(response?.message || 'Failed to get user status')
  }

  async getAllUsers(): Promise<any[]> {
    const response = await this.makeRequest(API_ENDPOINTS.getAllUsers)
    if (response?.success && response?.data) return response.data
    throw new Error(response?.message || 'Failed to get users')
  }

  async syncAttendances(attendances: any[]): Promise<{ success: boolean; syncedCount: number; totalCount: number; errors?: string[]; message?: string }> {
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

    if (response?.success) {
      return {
        success: true,
        syncedCount: response.data?.synced_count ?? attendances.length,
        totalCount: response.data?.total_count ?? attendances.length,
        errors: response.data?.errors || [],
        message: response.message
      }
    }
    throw new Error(response?.message || 'Sync failed')
  }

  async testConnection(): Promise<boolean> {
    try {
      await this.makeRequest('/offline-attendance/users', { method: 'GET' })
      return true
    } catch (error: any) {
      console.warn('Connection test failed:', error?.message || error)
      return false
    }
  }
}

export const api = new API()
export { API }
