// Tests for validation service
import { describe, it, expect, beforeEach } from 'vitest'
import { validationService } from '../validationService'

describe('ValidationService', () => {
  describe('validateUserid', () => {
    it('should validate a valid userid', () => {
      const result = validationService.validateUserid('USER001')
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
      expect(result.sanitizedData).toBe('USER001')
    })

    it('should reject empty userid', () => {
      const result = validationService.validateUserid('')
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('User ID is required')
    })

    it('should reject userid with invalid characters', () => {
      const result = validationService.validateUserid('USER@001')
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('User ID can only contain letters, numbers, hyphens, and underscores')
    })

    it('should reject userid that is too long', () => {
      const longUserid = 'A'.repeat(51)
      const result = validationService.validateUserid(longUserid)
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('User ID is too long (max 50 characters)')
    })

    it('should trim whitespace', () => {
      const result = validationService.validateUserid('  USER001  ')
      expect(result.isValid).toBe(true)
      expect(result.sanitizedData).toBe('USER001')
    })
  })

  describe('validateUserData', () => {
    it('should validate complete user data', () => {
      const userData = {
        userid: 'USER001',
        name: 'John Doe',
        alias_name: 'John'
      }

      const result = validationService.validateUserData(userData)
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
    })

    it('should validate user data with only userid', () => {
      const userData = {
        userid: 'USER001'
      }

      const result = validationService.validateUserData(userData)
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
    })

    it('should reject user data with invalid userid', () => {
      const userData = {
        userid: 'USER@001',
        name: 'John Doe'
      }

      const result = validationService.validateUserData(userData)
      expect(result.isValid).toBe(false)
      expect(result.errors.length).toBeGreaterThan(0)
    })
  })

  describe('validateAttendanceData', () => {
    it('should validate valid attendance data', () => {
      const attendanceData = {
        user_id: 1,
        type: 'Regular' as const,
        entry_date_time: new Date().toISOString()
      }

      const result = validationService.validateAttendanceData(attendanceData)
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
    })

    it('should reject attendance data with invalid user_id', () => {
      const attendanceData = {
        user_id: 0,
        type: 'Regular' as const
      }

      const result = validationService.validateAttendanceData(attendanceData)
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('Valid user ID is required')
    })

    it('should reject attendance data with invalid type', () => {
      const attendanceData = {
        user_id: 1,
        type: 'Invalid' as any
      }

      const result = validationService.validateAttendanceData(attendanceData)
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('Attendance type must be either "Regular" or "Overtime"')
    })

    it('should use current time if entry_date_time is not provided', () => {
      const attendanceData = {
        user_id: 1,
        type: 'Regular' as const
      }

      const result = validationService.validateAttendanceData(attendanceData)
      expect(result.isValid).toBe(true)
      expect(result.sanitizedData?.entry_date_time).toBeDefined()
    })
  })

  describe('validateBarcodeInput', () => {
    it('should validate valid barcode input', () => {
      const result = validationService.validateBarcodeInput('USER001')
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
    })

    it('should reject empty barcode input', () => {
      const result = validationService.validateBarcodeInput('')
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('Barcode input is required')
    })

    it('should reject barcode input with dangerous characters', () => {
      const result = validationService.validateBarcodeInput('USER<script>alert("xss")</script>')
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('Barcode input contains invalid characters')
    })
  })

  describe('validateApiResponse', () => {
    it('should validate valid API response', () => {
      const response = {
        success: true,
        data: { users: [] },
        message: 'Success'
      }

      const result = validationService.validateApiResponse(response, ['success', 'data'])
      expect(result.isValid).toBe(true)
      expect(result.errors).toHaveLength(0)
    })

    it('should reject API response missing required fields', () => {
      const response = {
        success: true
      }

      const result = validationService.validateApiResponse(response, ['success', 'data'])
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('Missing required field: data')
    })

    it('should reject non-object API response', () => {
      const result = validationService.validateApiResponse('invalid', ['success'])
      expect(result.isValid).toBe(false)
      expect(result.errors).toContain('API response must be an object')
    })
  })

  describe('sanitizeHtml', () => {
    it('should remove script tags', () => {
      const html = '<p>Hello</p><script>alert("xss")</script><p>World</p>'
      const result = validationService.sanitizeHtml(html)
      expect(result).toBe('<p>Hello</p><p>World</p>')
    })

    it('should remove iframe tags', () => {
      const html = '<p>Hello</p><iframe src="malicious.com"></iframe><p>World</p>'
      const result = validationService.sanitizeHtml(html)
      expect(result).toBe('<p>Hello</p><p>World</p>')
    })

    it('should remove event handlers', () => {
      const html = '<p onclick="alert(\'xss\')">Hello</p>'
      const result = validationService.sanitizeHtml(html)
      expect(result).toBe('<p>Hello</p>')
    })
  })
})
