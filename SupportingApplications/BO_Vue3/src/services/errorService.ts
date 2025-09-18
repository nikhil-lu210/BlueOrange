// Error handling and logging service
import { DEBUG_MODE } from '../utils/constants'

export interface AppError {
  code: string
  message: string
  details?: any
  timestamp: string
  stack?: string
}

export interface ErrorContext {
  component?: string
  action?: string
  userId?: string
  userid?: string
  additionalData?: any
}

class ErrorService {
  private errors: AppError[] = []
  private maxErrors = 100

  logError(error: Error | string, context?: ErrorContext): AppError {
    const appError: AppError = {
      code: this.generateErrorCode(),
      message: typeof error === 'string' ? error : error.message,
      details: context,
      timestamp: new Date().toISOString(),
      stack: typeof error === 'object' && error.stack ? error.stack : undefined
    }

    this.errors.push(appError)

    // Keep only the last maxErrors
    if (this.errors.length > this.maxErrors) {
      this.errors = this.errors.slice(-this.maxErrors)
    }

    // Log to console in debug mode
    if (DEBUG_MODE) {
      console.error('🚨 App Error:', appError)
    }

    // Store in localStorage for persistence
    this.persistErrors()

    return appError
  }

  logWarning(message: string, context?: ErrorContext): void {
    const warning = {
      code: 'WARNING',
      message,
      details: context,
      timestamp: new Date().toISOString()
    }

    if (DEBUG_MODE) {
      console.warn('⚠️ App Warning:', warning)
    }
  }

  logInfo(message: string, context?: ErrorContext): void {
    if (DEBUG_MODE) {
      console.info('ℹ️ App Info:', { message, context, timestamp: new Date().toISOString() })
    }
  }

  getErrors(): AppError[] {
    return [...this.errors]
  }

  getRecentErrors(count: number = 10): AppError[] {
    return this.errors.slice(-count)
  }

  clearErrors(): void {
    this.errors = []
    this.persistErrors()
  }

  getUserFriendlyMessage(error: AppError): string {
    // Map technical errors to user-friendly messages
    const errorMessages: Record<string, string> = {
      'NETWORK_ERROR': 'Unable to connect to the server. Please check your internet connection.',
      'TIMEOUT_ERROR': 'The request took too long to complete. Please try again.',
      'USER_NOT_FOUND': 'User not found in the system. Please sync with the server first.',
      'SYNC_FAILED': 'Failed to sync data with the server. Please try again later.',
      'STORAGE_ERROR': 'Unable to save data locally. Please check your browser storage.',
      'VALIDATION_ERROR': 'Invalid data provided. Please check your input.',
      'PERMISSION_ERROR': 'You do not have permission to perform this action.',
      'SERVER_ERROR': 'Server error occurred. Please try again later.',
      'UNKNOWN_ERROR': 'An unexpected error occurred. Please try again.'
    }

    return errorMessages[error.code] || error.message || 'An unexpected error occurred.'
  }

  private generateErrorCode(): string {
    const codes = [
      'NETWORK_ERROR',
      'TIMEOUT_ERROR',
      'USER_NOT_FOUND',
      'SYNC_FAILED',
      'STORAGE_ERROR',
      'VALIDATION_ERROR',
      'PERMISSION_ERROR',
      'SERVER_ERROR',
      'UNKNOWN_ERROR'
    ]

    return codes[Math.floor(Math.random() * codes.length)]
  }

  private persistErrors(): void {
    try {
      localStorage.setItem('bo_attendance_errors', JSON.stringify(this.errors))
    } catch (error) {
      console.error('Failed to persist errors:', error)
    }
  }

  private loadPersistedErrors(): void {
    try {
      const stored = localStorage.getItem('bo_attendance_errors')
      if (stored) {
        this.errors = JSON.parse(stored)
      }
    } catch (error) {
      console.error('Failed to load persisted errors:', error)
      this.errors = []
    }
  }

  // Initialize error service
  init(): void {
    this.loadPersistedErrors()
  }
}

export const errorService = new ErrorService()
export default errorService
