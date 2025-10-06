// Application constants and environment variables (React/Vite compatible)

export const APP_NAME = import.meta.env.VITE_APP_NAME || 'BlueOrange Offline Attendance'
export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://blueorange.test/api'
export const API_TIMEOUT = Number(import.meta.env.VITE_API_TIMEOUT || 60000)
export const DB_NAME = import.meta.env.VITE_DB_NAME || 'blueorange_offline'
export const DEBUG_MODE = (import.meta.env.VITE_DEBUG_MODE || 'false') === 'true'

// Helper function to get server name (extracted from app name)
export const getServerName = (): string => {
  const parts = APP_NAME.split(' ')
  return parts[0] || 'Server'
}

// Helper function to get short app name
export const getShortAppName = (): string => {
  return getServerName()
}
