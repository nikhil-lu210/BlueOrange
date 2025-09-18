// API utility for communicating with Laravel backend
import { getServerName } from './constants'
import { errorService } from '../services/errorService'
import { validationService } from '../services/validationService'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://blueorange.test/api'
const API_TIMEOUT = import.meta.env.VITE_API_TIMEOUT || 60000
const API_ENDPOINTS = {
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
    errorService.logInfo(`Making API request to: ${url}`)

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
        errorService.logWarning(`Request timeout after ${API_TIMEOUT}ms for: ${url}`)
        controller.abort()
      }, API_TIMEOUT)

      const response = await fetch(url, {
        ...defaultOptions,
        signal: controller.signal
      })

      clearTimeout(timeoutId)

      if (!response.ok) {
        const errorText = await response.text()
        const error = new Error(`HTTP error! status: ${response.status} - ${errorText}`)
        errorService.logError(error, { action: 'api_request', additionalData: { url, status: response.status } })
        throw error
      }

      const data = await response.json()
      errorService.logInfo(`API request successful: ${url}`)
      return data
    } catch (error) {
      if (error instanceof Error) {
        if (error.name === 'AbortError') {
          const timeoutError = new Error(`Request timeout after ${API_TIMEOUT}ms`)
          errorService.logError(timeoutError, { action: 'api_timeout', additionalData: { url } })
          throw timeoutError
        }
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
          const connectionError = new Error(`Cannot connect to ${getServerName()} server. Please check if the server is running at ${this.baseURL}`)
          errorService.logError(connectionError, { action: 'api_connection', additionalData: { url } })
          throw connectionError
        }
        errorService.logError(error, { action: 'api_request', additionalData: { url } })
        throw error
      } else {
        const unknownError = new Error('Unknown error occurred')
        errorService.logError(unknownError, { action: 'api_request', additionalData: { url } })
        throw unknownError
      }
    }
  }


  async getAllUsers(): Promise<any[]> {
    try {
      const response = await this.makeRequest(API_ENDPOINTS.getAllUsers)

      // Validate API response
      const validation = validationService.validateApiResponse(response, ['success', 'data'])
      if (!validation.isValid) {
        throw new Error(`Invalid API response: ${validation.errors.join(', ')}`)
      }

      if (response.success && response.data) {
        // Validate and sanitize user data
        const sanitizedUsers = response.data.map((user: any) => {
          const userValidation = validationService.validateUserData({
            userid: user.userid,
            name: user.name,
            alias_name: user.alias_name
          })

          if (!userValidation.isValid) {
            errorService.logWarning(`Invalid user data: ${userValidation.errors.join(', ')}`, {
              action: 'validate_user_data',
              userid: user.userid
            })
          }

          return userValidation.sanitizedData || user
        })

        return sanitizedUsers
      } else {
        throw new Error(response.message || 'Failed to get users')
      }
    } catch (error) {
      const errorToLog = error instanceof Error ? error : new Error('Unknown error occurred')
      errorService.logError(errorToLog, { action: 'get_all_users' })
      throw error
    }
  }

  async syncAttendances(attendances: any[]): Promise<any> {
    try {
      // Validate attendance data before sending
      const validatedAttendances = attendances.map(att => {
        const validation = validationService.validateAttendanceData({
          user_id: att.user_id,
          type: att.type,
          entry_date_time: att.entry_date_time
        })

        if (!validation.isValid) {
          errorService.logWarning(`Invalid attendance data: ${validation.errors.join(', ')}`, {
            action: 'validate_attendance_data',
            userId: att.user_id
          })
        }

        return validation.sanitizedData || att
      })

      const response = await this.makeRequest(API_ENDPOINTS.syncAttendances, {
        method: 'POST',
        body: JSON.stringify({
          attendances: validatedAttendances.map(att => ({
            user_id: att.user_id,
            entry_date_time: att.entry_date_time,
            type: att.type
          }))
        })
      })

      // Validate API response
      const validation = validationService.validateApiResponse(response, ['success'])
      if (!validation.isValid) {
        throw new Error(`Invalid API response: ${validation.errors.join(', ')}`)
      }

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
      const errorToLog = error instanceof Error ? error : new Error('Unknown error occurred')
      errorService.logError(errorToLog, { action: 'sync_attendances' })
      throw error
    }
  }

  async testConnection(): Promise<boolean> {
    try {
      errorService.logInfo(`Testing connection to ${getServerName()} server...`)
      await this.makeRequest('/offline-attendance/users', { method: 'GET' })
      errorService.logInfo('Connection test successful')
      return true
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : 'Unknown error'
      errorService.logWarning(`Connection test failed: ${errorMessage}`)
      return false
    }
  }
}

export const api = new API()
export { API }
