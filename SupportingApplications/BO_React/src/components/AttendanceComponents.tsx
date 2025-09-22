import React, { useEffect, useMemo, useRef, useState } from 'react'
import { smartDbService as dbService } from '../services/smartDb'

// ========== Types ==========
export type ToastType = 'success' | 'error' | 'warning' | 'info'
export type AttendanceType = 'Regular' | 'Overtime'

export interface Attendance {
  id: number
  user_id: number
  entry_date_time: string
  type: AttendanceType
  synced: boolean
  created_at: string
}

export interface User {
  id: number
  userid: string
  name: string
  alias_name: string
  email?: string
}

// ========== StatusBar ==========
export function StatusBar({ status, type = 'info', loading = false }: { status: string; type?: 'info' | 'success' | 'warning' | 'error' | 'loading'; loading?: boolean }) {
  return (
    <div className="status-bar">
      <div className="d-flex align-items-center justify-content-between">
        <div className="d-flex align-items-center">
          <i className="bi bi-info-circle me-2"></i>
          <span className="fw-medium">{status}</span>
        </div>
        {loading && <div className="loading-spinner"></div>}
      </div>
    </div>
  )
}

// ========== StatsCards ==========
export function StatsCards({ total, pending, synced, users }: { total: number; pending: number; synced: number; users: number }) {
  return (
    <div className="row">
      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-people"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Total Active Users</div>
              <div className="h5 mb-0">{users}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-clock-history"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Total Records</div>
              <div className="h5 mb-0">{total}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-exclamation-triangle"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Pending Sync</div>
              <div className="h5 mb-0">{pending}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-check-circle"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Synced</div>
              <div className="h5 mb-0">{synced}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

// ========== Scanner ==========
export function Scanner({ currentUser, loading, onUserScanned, onRecordEntry, onClearUser }: { currentUser: (User & { suggestedType?: AttendanceType }) | null; loading: boolean; onUserScanned: (userid: string) => void; onRecordEntry: (type: AttendanceType) => void; onClearUser: () => void }) {
  const [barcodeValue, setBarcodeValue] = useState('')
  const inputRef = useRef<HTMLInputElement | null>(null)
  const debounceRef = useRef<number | null>(null)

  useEffect(() => {
    inputRef.current?.focus()
    return () => {
      if (debounceRef.current) {
        window.clearTimeout(debounceRef.current)
      }
    }
  }, [])

  const handleInput = (value: string) => {
    setBarcodeValue(value)
    if (debounceRef.current) {
      clearTimeout(debounceRef.current)
    }
    debounceRef.current = window.setTimeout(() => {
      if (value.trim()) handleScan()
    }, 500)
  }

  const handleScan = () => {
    const userid = barcodeValue.trim()
    if (userid && !loading) {
      onUserScanned(userid)
      setBarcodeValue('')
    }
  }

  const clearUser = () => {
    onClearUser()
    setBarcodeValue('')
    setTimeout(() => inputRef.current?.focus(), 0)
  }

  return (
    <div className="scanner-panel p-4">
      <h5 className="mb-3">
        <i className="bi bi-qr-code-scan me-2"></i>
        Barcode Scanner
      </h5>

      <div className="mb-3">
        <label htmlFor="barcodeInput" className="form-label">Scan or Enter User ID</label>
        <input
          id="barcodeInput"
          ref={inputRef}
          value={barcodeValue}
          onChange={(e) => handleInput(e.target.value)}
          type="text"
          className="form-control barcode-input"
          placeholder="Scan barcode or enter user ID"
          disabled={loading}
          onKeyUp={(e) => {
            if (e.key === 'Enter') handleScan()
          }}
        />
      </div>

      {currentUser ? (
        <div className="user-info">
          <div className="d-flex align-items-center justify-content-between">
            <div>
              <h6 className="mb-1">{currentUser.alias_name}</h6>
              <small className="opacity-75">{currentUser.name} ({currentUser.userid})</small>
            </div>
            <button className="btn btn-outline-light btn-sm" onClick={clearUser} disabled={loading}>
              <i className="bi bi-x"></i>
            </button>
          </div>
        </div>
      ) : (
        <div className="text-muted small mt-3">
          <i className="bi bi-info-circle me-1"></i>
          Scan a barcode or enter a user ID to begin
        </div>
      )}

      {currentUser && (
        <div className="mt-3">
          <div className="row g-2">
            <div className="col-12">
              <button
                className="btn btn-primary btn-action w-100"
                onClick={() => onRecordEntry((currentUser.suggestedType || 'Regular'))}
                disabled={loading}
              >
                <i className="bi bi-plus-circle me-1"></i>
                Record {currentUser.suggestedType || 'Regular'} Entry
              </button>
            </div>
            {currentUser.suggestedType === 'Regular' && (
              <div className="col-12">
                <button className="btn btn-warning btn-action w-100" onClick={() => onRecordEntry('Overtime')} disabled={loading}>
                  <i className="bi bi-plus-circle me-1"></i>
                  Record Overtime Entry
                </button>
              </div>
            )}
            {currentUser.suggestedType === 'Overtime' && (
              <div className="col-12">
                <button className="btn btn-success btn-action w-100" onClick={() => onRecordEntry('Regular')} disabled={loading}>
                  <i className="bi bi-plus-circle me-1"></i>
                  Record Regular Entry
                </button>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  )
}

// ========== AttendanceTable ==========
export function AttendanceTable({ attendances, loading, onDeleteAttendance, onClearAllAttendances }: { attendances: Attendance[]; loading: boolean; onDeleteAttendance: (id: number) => void; onClearAllAttendances: () => void }) {
  const [users, setUsers] = useState<User[]>([])

  useEffect(() => {
    let mounted = true
    ;(async () => {
      try {
        const u = await dbService.getAllUsers()
        if (mounted) setUsers(u)
      } catch (e) {
        console.error('Failed to load users', e)
      }
    })()
    return () => { mounted = false }
  }, [])

  const getUserInfo = (userId: number): User | undefined => users.find(u => u.id === userId)

  const formatDate = (dateString: string): string => {
    try { return new Date(dateString).toLocaleDateString() } catch { return 'Invalid Date' }
  }

  const formatTime = (timeString: string): string => {
    try {
      const date = new Date(timeString)
      if (isNaN(date.getTime())) return timeString
      return date.toLocaleTimeString()
    } catch { return 'Invalid Time' }
  }

  return (
    <div className="attendance-panel p-4">
      <div className="d-flex align-items-center justify-content-between mb-3">
        <h5 className="mb-0">
          <i className="bi bi-table me-2"></i>
          Attendance Records
        </h5>
        <div className="d-flex align-items-center gap-2">
          {attendances.length > 0 && (
            <button className="btn btn-outline-danger btn-sm" onClick={() => !loading && onClearAllAttendances()} disabled={loading} title="Clear all attendance records">
              <i className="bi bi-trash"></i>
              {" "}Clear All
            </button>
          )}
          {loading && <div className="loading-spinner"></div>}
        </div>
      </div>

      {attendances.length === 0 ? (
        <div className="text-center text-muted py-4">
          <i className="bi bi-inbox display-4 d-block mb-2"></i>
          <p className="mb-0">No attendance records found</p>
          <small>Scan a barcode to start recording attendance</small>
        </div>
      ) : (
        <div className="table-responsive">
          <table className="table table-attendance">
            <thead>
              <tr>
                <th>User</th>
                <th>Type</th>
                <th>Entry Time</th>
                <th>Synced</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {attendances.map((attendance) => (
                <tr key={attendance.id}>
                  <td>
                    <div>
                      <div className="fw-medium">{getUserInfo(attendance.user_id)?.alias_name || 'Unknown User'}</div>
                      <small className="text-muted">{getUserInfo(attendance.user_id)?.userid || `ID: ${attendance.user_id}`}</small>
                    </div>
                  </td>
                  <td>
                    <span className={`badge ${attendance.type === 'Overtime' ? 'bg-warning' : 'bg-primary'}`}>
                      {attendance.type || 'Regular'}
                    </span>
                  </td>
                  <td>
                    <div>
                      <div>{formatDate(attendance.entry_date_time)}</div>
                      <small className="text-muted">{formatTime(attendance.entry_date_time)}</small>
                    </div>
                  </td>
                  <td>
                    <span className={`badge ${attendance.synced ? 'badge-synced' : 'badge-pending'}`}>
                      <i className={`bi ${attendance.synced ? 'bi-check-circle' : 'bi-clock-history'}`}></i>
                      {" "}{attendance.synced ? 'Synced' : 'Pending'}
                    </span>
                  </td>
                  <td>
                    <button className="btn btn-outline-danger btn-sm" onClick={() => { if (!loading && confirm('Are you sure you want to delete this attendance record?')) onDeleteAttendance(attendance.id) }} disabled={loading} title="Delete record">
                      <i className="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}

// ========== Toast ==========
interface ToastItem { id: number; type: ToastType; message: string }

export function ToastContainer() {
  const [toasts, setToasts] = useState<ToastItem[]>([])
  const nextIdRef = useRef(1)

  useEffect(() => {
    const show = (message: string, type: ToastType = 'info') => {
      const id = nextIdRef.current++
      const toast = { id, type, message }
      setToasts((prev) => [...prev, toast])
      window.setTimeout(() => removeToast(id), 5000)
    }

    const removeToast = (id: number) => {
      setToasts((prev) => prev.filter((t) => t.id !== id))
    }

    ;(window as any).Toast = { show }

    return () => { (window as any).Toast = undefined }
  }, [])

  const getIcon = (type: ToastType) => {
    const icons: Record<ToastType, string> = {
      success: 'bi-check-circle-fill text-success',
      error: 'bi-exclamation-triangle-fill text-danger',
      warning: 'bi-exclamation-triangle-fill text-warning',
      info: 'bi-info-circle-fill text-info'
    }
    return icons[type]
  }

  const getTitle = (type: ToastType) => {
    const titles: Record<ToastType, string> = {
      success: 'Success',
      error: 'Error',
      warning: 'Warning',
      info: 'Info'
    }
    return titles[type]
  }

  return (
    <div className="toast-container">
      {toasts.map((toast) => (
        <div key={toast.id} className={`toast show toast-${toast.type}`} role="alert">
          <div className="toast-header">
            <i className={`bi me-2 ${getIcon(toast.type)}`}></i>
            <strong className="me-auto">{getTitle(toast.type)}</strong>
            {/* Close button intentionally not wired for simplicity; auto-dismiss handles it */}
          </div>
          <div className="toast-body">{toast.message}</div>
        </div>
      ))}
    </div>
  )
}
