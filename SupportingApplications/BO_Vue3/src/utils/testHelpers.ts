// Test utilities and helpers for the application

export interface MockUser {
  id: number
  userid: string
  name: string
  alias_name: string
}

export interface MockAttendance {
  id: number
  user_id: number
  entry_date_time: string
  type: 'Regular' | 'Overtime'
  synced: boolean
  created_at: string
}

export class TestDataGenerator {
  static generateMockUsers(count: number = 10): MockUser[] {
    const users: MockUser[] = []

    for (let i = 1; i <= count; i++) {
      users.push({
        id: i,
        userid: `USER${i.toString().padStart(3, '0')}`,
        name: `User ${i}`,
        alias_name: `User ${i}`
      })
    }

    return users
  }

  static generateMockAttendances(users: MockUser[], count: number = 20): MockAttendance[] {
    const attendances: MockAttendance[] = []

    for (let i = 1; i <= count; i++) {
      const randomUser = users[Math.floor(Math.random() * users.length)]
      const now = new Date()
      const randomTime = new Date(now.getTime() - Math.random() * 7 * 24 * 60 * 60 * 1000) // Random time within last 7 days

      attendances.push({
        id: i,
        user_id: randomUser.id,
        entry_date_time: randomTime.toISOString(),
        type: Math.random() > 0.8 ? 'Overtime' : 'Regular',
        synced: Math.random() > 0.3,
        created_at: randomTime.toISOString()
      })
    }

    return attendances
  }

  static generateMockApiResponse(success: boolean = true, data?: any, message?: string) {
    return {
      success,
      data: data || null,
      message: message || (success ? 'Success' : 'Error occurred')
    }
  }
}

export class TestValidator {
  static validateUserData(user: any): boolean {
    return (
      user &&
      typeof user.id === 'number' &&
      typeof user.userid === 'string' &&
      typeof user.name === 'string' &&
      typeof user.alias_name === 'string'
    )
  }

  static validateAttendanceData(attendance: any): boolean {
    return (
      attendance &&
      typeof attendance.id === 'number' &&
      typeof attendance.user_id === 'number' &&
      typeof attendance.entry_date_time === 'string' &&
      ['Regular', 'Overtime'].includes(attendance.type) &&
      typeof attendance.synced === 'boolean'
    )
  }

  static validateApiResponse(response: any): boolean {
    return (
      response &&
      typeof response.success === 'boolean' &&
      (response.data !== undefined || response.message !== undefined)
    )
  }
}

export class MockLocalStorage {
  private store: Record<string, string> = {}

  getItem(key: string): string | null {
    return this.store[key] || null
  }

  setItem(key: string, value: string): void {
    this.store[key] = value
  }

  removeItem(key: string): void {
    delete this.store[key]
  }

  clear(): void {
    this.store = {}
  }

  get length(): number {
    return Object.keys(this.store).length
  }

  key(index: number): string | null {
    const keys = Object.keys(this.store)
    return keys[index] || null
  }
}

export class TestEnvironment {
  static setupMockEnvironment(): void {
    // Mock localStorage
    const mockLocalStorage = new MockLocalStorage()
    Object.defineProperty(window, 'localStorage', {
      value: mockLocalStorage,
      writable: true
    })

    // Mock fetch
    global.fetch = jest.fn()

    // Mock console methods to avoid noise in tests
    global.console = {
      ...console,
      log: jest.fn(),
      warn: jest.fn(),
      error: jest.fn(),
      info: jest.fn()
    }
  }

  static cleanupMockEnvironment(): void {
    // Clean up mocks
    if (global.fetch && jest.isMockFunction(global.fetch)) {
      (global.fetch as jest.Mock).mockRestore()
    }
  }
}

export class PerformanceTester {
  static async measureExecutionTime<T>(
    fn: () => Promise<T> | T,
    label: string = 'Operation'
  ): Promise<{ result: T; duration: number }> {
    const start = performance.now()
    const result = await fn()
    const end = performance.now()
    const duration = end - start

    console.log(`${label} took ${duration.toFixed(2)}ms`)
    return { result, duration }
  }

  static async measureMemoryUsage(): Promise<{
    usedJSHeapSize: number
    totalJSHeapSize: number
    jsHeapSizeLimit: number
  }> {
    if ('memory' in performance) {
      const memory = (performance as any).memory
      return {
        usedJSHeapSize: memory.usedJSHeapSize,
        totalJSHeapSize: memory.totalJSHeapSize,
        jsHeapSizeLimit: memory.jsHeapSizeLimit
      }
    }

    return {
      usedJSHeapSize: 0,
      totalJSHeapSize: 0,
      jsHeapSizeLimit: 0
    }
  }
}

export class AccessibilityTester {
  static checkAriaLabels(element: HTMLElement): string[] {
    const issues: string[] = []

    // Check buttons without aria-label
    const buttons = element.querySelectorAll('button')
    buttons.forEach((button, index) => {
      if (!button.getAttribute('aria-label') && !button.textContent?.trim()) {
        issues.push(`Button at index ${index} is missing aria-label`)
      }
    })

    // Check inputs without labels
    const inputs = element.querySelectorAll('input')
    inputs.forEach((input, index) => {
      const id = input.getAttribute('id')
      if (id && !element.querySelector(`label[for="${id}"]`)) {
        issues.push(`Input at index ${index} is missing associated label`)
      }
    })

    return issues
  }

  static checkColorContrast(element: HTMLElement): string[] {
    const issues: string[] = []

    // This is a simplified check - in a real implementation,
    // you'd use a library like axe-core for comprehensive accessibility testing

    const elements = element.querySelectorAll('*')
    elements.forEach((el, index) => {
      const computedStyle = window.getComputedStyle(el)
      const color = computedStyle.color
      const backgroundColor = computedStyle.backgroundColor

      // Basic check for contrast (simplified)
      if (color && backgroundColor && color === backgroundColor) {
        issues.push(`Element at index ${index} has poor color contrast`)
      }
    })

    return issues
  }
}

export default {
  TestDataGenerator,
  TestValidator,
  MockLocalStorage,
  TestEnvironment,
  PerformanceTester,
  AccessibilityTester
}
