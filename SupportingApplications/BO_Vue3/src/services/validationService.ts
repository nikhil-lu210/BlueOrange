// Data validation and sanitization service
import { errorService } from './errorService'

export interface ValidationResult {
  isValid: boolean
  errors: string[]
  sanitizedData?: any
}

export interface UserValidationData {
  userid: string
  name?: string
  alias_name?: string
}

export interface AttendanceValidationData {
  user_id: number
  type: 'Regular' | 'Overtime'
  entry_date_time?: string
}

class ValidationService {

  // User ID validation
  validateUserid(userid: string): ValidationResult {
    const errors: string[] = []

    if (!userid) {
      errors.push('User ID is required')
      return { isValid: false, errors }
    }

    const sanitized = userid.trim()

    if (sanitized.length === 0) {
      errors.push('User ID cannot be empty')
    }

    if (sanitized.length > 50) {
      errors.push('User ID is too long (max 50 characters)')
    }

    // Check for valid characters (alphanumeric, hyphens, underscores)
    if (!/^[a-zA-Z0-9_-]+$/.test(sanitized)) {
      errors.push('User ID can only contain letters, numbers, hyphens, and underscores')
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: sanitized
    }
  }

  // User data validation
  validateUserData(userData: UserValidationData): ValidationResult {
    const errors: string[] = []
    const sanitized: any = {}

    // Validate userid
    const useridResult = this.validateUserid(userData.userid)
    if (!useridResult.isValid) {
      errors.push(...useridResult.errors)
    } else {
      sanitized.userid = useridResult.sanitizedData
    }

    // Validate name (optional)
    if (userData.name !== undefined) {
      const nameResult = this.validateName(userData.name)
      if (!nameResult.isValid) {
        errors.push(...nameResult.errors)
      } else {
        sanitized.name = nameResult.sanitizedData
      }
    }

    // Validate alias_name (optional)
    if (userData.alias_name !== undefined) {
      const aliasResult = this.validateName(userData.alias_name)
      if (!aliasResult.isValid) {
        errors.push(...aliasResult.errors)
      } else {
        sanitized.alias_name = aliasResult.sanitizedData
      }
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: sanitized
    }
  }

  // Name validation
  validateName(name: string): ValidationResult {
    const errors: string[] = []

    if (name === undefined || name === null) {
      return { isValid: true, errors, sanitizedData: '' }
    }

    const sanitized = name.trim()

    if (sanitized.length > 100) {
      errors.push('Name is too long (max 100 characters)')
    }

    // Check for potentially dangerous characters
    if (/[<>\"'&]/.test(sanitized)) {
      errors.push('Name contains invalid characters')
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: sanitized
    }
  }

  // Attendance data validation
  validateAttendanceData(attendanceData: AttendanceValidationData): ValidationResult {
    const errors: string[] = []
    const sanitized: any = {}

    // Validate user_id
    if (!attendanceData.user_id || typeof attendanceData.user_id !== 'number' || attendanceData.user_id <= 0) {
      errors.push('Valid user ID is required')
    } else {
      sanitized.user_id = attendanceData.user_id
    }

    // Validate type
    if (!attendanceData.type || !['Regular', 'Overtime'].includes(attendanceData.type)) {
      errors.push('Attendance type must be either "Regular" or "Overtime"')
    } else {
      sanitized.type = attendanceData.type
    }

    // Validate entry_date_time (optional)
    if (attendanceData.entry_date_time) {
      const dateResult = this.validateDateTime(attendanceData.entry_date_time)
      if (!dateResult.isValid) {
        errors.push(...dateResult.errors)
      } else {
        sanitized.entry_date_time = dateResult.sanitizedData
      }
    } else {
      // Use current time if not provided
      sanitized.entry_date_time = new Date().toISOString()
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: sanitized
    }
  }

  // DateTime validation
  validateDateTime(dateTime: string): ValidationResult {
    const errors: string[] = []

    if (!dateTime) {
      errors.push('Date time is required')
      return { isValid: false, errors }
    }

    const date = new Date(dateTime)

    if (isNaN(date.getTime())) {
      errors.push('Invalid date format')
      return { isValid: false, errors }
    }

    // Check if date is not too far in the future (more than 1 day)
    const now = new Date()
    const oneDayFromNow = new Date(now.getTime() + 24 * 60 * 60 * 1000)

    if (date > oneDayFromNow) {
      errors.push('Date cannot be more than 1 day in the future')
    }

    // Check if date is not too far in the past (more than 30 days)
    const thirtyDaysAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000)

    if (date < thirtyDaysAgo) {
      errors.push('Date cannot be more than 30 days in the past')
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: date.toISOString()
    }
  }

  // Barcode input validation
  validateBarcodeInput(input: string): ValidationResult {
    const errors: string[] = []

    if (!input) {
      errors.push('Barcode input is required')
      return { isValid: false, errors }
    }

    const sanitized = input.trim()

    if (sanitized.length === 0) {
      errors.push('Barcode input cannot be empty')
    }

    if (sanitized.length > 100) {
      errors.push('Barcode input is too long')
    }

    // Check for potentially dangerous characters
    if (/[<>\"'&]/.test(sanitized)) {
      errors.push('Barcode input contains invalid characters')
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: sanitized
    }
  }

  // API response validation
  validateApiResponse(response: any, expectedFields: string[]): ValidationResult {
    const errors: string[] = []

    if (!response) {
      errors.push('API response is empty')
      return { isValid: false, errors }
    }

    if (typeof response !== 'object') {
      errors.push('API response must be an object')
      return { isValid: false, errors }
    }

    // Check for required fields
    for (const field of expectedFields) {
      if (!(field in response)) {
        errors.push(`Missing required field: ${field}`)
      }
    }

    return {
      isValid: errors.length === 0,
      errors,
      sanitizedData: response
    }
  }

  // Sanitize HTML content
  sanitizeHtml(html: string): string {
    if (!html) return ''

    // Remove potentially dangerous HTML tags and attributes
    return html
      .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
      .replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi, '')
      .replace(/on\w+="[^"]*"/gi, '')
      .replace(/javascript:/gi, '')
      .trim()
  }

  // Validate and sanitize all user input
  sanitizeUserInput(input: any): any {
    if (typeof input === 'string') {
      return this.sanitizeHtml(input.trim())
    }

    if (Array.isArray(input)) {
      return input.map(item => this.sanitizeUserInput(item))
    }

    if (typeof input === 'object' && input !== null) {
      const sanitized: any = {}
      for (const [key, value] of Object.entries(input)) {
        sanitized[key] = this.sanitizeUserInput(value)
      }
      return sanitized
    }

    return input
  }
}

export const validationService = new ValidationService()
export default validationService
