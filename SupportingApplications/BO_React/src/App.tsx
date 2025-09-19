import { useEffect, useState } from 'react'
import './App.css'
import './assets/css/app.css'
import { StatusBar, StatsCards, Scanner, AttendanceTable, ToastContainer } from './components/BOUI'
import { useAttendanceStore } from './bo/stores/attendance'
import { useUsersStore } from './bo/stores/users'
import { workflowService } from './bo/services/workflowService'
import { getServerName, APP_NAME } from './bo/utils/constants'

export default function App() {
  const attendanceStore = useAttendanceStore()
  const usersStore = useUsersStore()

  const [isOnline, setIsOnline] = useState<boolean>(typeof navigator !== 'undefined' ? navigator.onLine : true)
  const [currentUser, setCurrentUser] = useState<any>(null)
  const [loading, setLoading] = useState(false)
  const [status, setStatus] = useState('Ready to scan attendance')
  const [statusType, setStatusType] = useState<'info' | 'success' | 'warning' | 'error' | 'loading'>('info')

  const attendances = attendanceStore.attendances as any[]
  const users = usersStore.users as any[]
  const unsyncedCount = attendanceStore.unsyncedCount || 0
  const totalCount = attendanceStore.totalCount || 0
  const syncedCount = attendanceStore.syncedCount || 0

  const updateStatus = (message: string, type: 'info' | 'success' | 'warning' | 'error' | 'loading' = 'info') => {
    setStatus(message)
    setStatusType(type)
  }

  const handleUserScanned = async (userid: string) => {
    try {
      setLoading(true)
      updateStatus('Looking up user...', 'loading')
      const result = await workflowService.handleUserScan(userid)
      if (result.action === 'not_found') {
        setCurrentUser(null)
        updateStatus(result.message, 'error')
        return
      }
      setCurrentUser(result.user)
      updateStatus(result.message, 'success')
    } catch (e) {
      console.error(e)
      setCurrentUser(null)
      updateStatus('Error looking up user', 'error')
    } finally {
      setLoading(false)
    }
  }

  const handleRecordEntry = async (type: 'Regular' | 'Overtime') => {
    if (!currentUser) return
    try {
      setLoading(true)
      const result = await workflowService.recordUserEntry(currentUser.id, type)
      if (result.success) {
        updateStatus(result.message, 'success')
        setCurrentUser(null)
        await attendanceStore.loadAttendances()
        ;(window as any).Toast?.show('Entry recorded successfully', 'success')
      } else {
        updateStatus(result.message, 'error')
        ;(window as any).Toast?.show(result.message, 'error')
      }
    } catch (e) {
      console.error(e)
      updateStatus('Failed to record entry', 'error')
      ;(window as any).Toast?.show('Failed to record entry', 'error')
    } finally {
      setLoading(false)
    }
  }

  const syncAttendances = async () => {
    if (!isOnline) {
      updateStatus('No internet connection', 'error')
      return
    }
    try {
      setLoading(true)
      updateStatus(`Syncing offline attendances to ${getServerName()}...`, 'loading')
      const result = await workflowService.syncOfflineData()
      if (result.success) {
        updateStatus(`Synced ${result.syncedCount} records to ${getServerName()}`, 'success')
        ;(window as any).Toast?.show(`Successfully synced ${result.syncedCount} records`, 'success')
        await attendanceStore.loadAttendances()
      } else {
        updateStatus('Sync failed', 'error')
        ;(window as any).Toast?.show('Sync failed: ' + (result.message || ''), 'error')
      }
    } catch (e) {
      console.error(e)
      updateStatus('Sync failed', 'error')
      ;(window as any).Toast?.show('Sync failed', 'error')
    } finally {
      setLoading(false)
    }
  }

  const syncActiveUsers = async () => {
    if (!isOnline) {
      updateStatus('No internet connection. Working offline.', 'warning')
      return
    }
    try {
      setLoading(true)
      updateStatus(`Syncing active users from ${getServerName()}...`, 'loading')
      const result = await workflowService.syncActiveUsers((progress) => {
        updateStatus(progress.message, 'loading')
      })
      updateStatus(`Active users sync completed: ${result.totalUsers} users synced`, 'success')
      ;(window as any).Toast?.show(`Synced ${result.totalUsers} active users from ${getServerName()}`, 'success')
      await attendanceStore.loadAttendances()
      await usersStore.loadUsers()
    } catch (e) {
      console.error(e)
      updateStatus('Active users sync failed', 'error')
      ;(window as any).Toast?.show('Active users sync failed', 'error')
    } finally {
      setLoading(false)
    }
  }

  const deleteAttendance = async (id: number) => {
    try {
      await attendanceStore.deleteAttendance(id)
      ;(window as any).Toast?.show('Attendance record deleted', 'success')
    } catch (e) {
      console.error(e)
      ;(window as any).Toast?.show('Failed to delete attendance record', 'error')
    }
  }

  const clearAllAttendances = async () => {
    try {
      await attendanceStore.clearAll()
      ;(window as any).Toast?.show('All attendance records cleared', 'success')
      updateStatus('All attendance records cleared', 'success')
    } catch (e) {
      console.error(e)
      ;(window as any).Toast?.show('Failed to clear attendance records', 'error')
    }
  }

  const clearUser = () => {
    setCurrentUser(null)
    updateStatus('Ready to scan attendance', 'info')
  }

  useEffect(() => {
    const updateOnline = () => {
      const online = navigator.onLine
      setIsOnline(online)
      updateStatus(online ? 'Connected to server' : 'Working offline', online ? 'success' : 'warning')
    }

    (async () => {
      try {
        const status = await workflowService.initializeApp()
        await attendanceStore.loadAttendances()
        await usersStore.loadUsers()

        updateOnline()

        if (navigator.onLine && !status.hasUsers) {
          updateStatus(`No users found. Syncing active users from ${getServerName()}...`, 'loading')
          await syncActiveUsers()
        } else if (!status.hasUsers) {
          updateStatus(`No users found. Click "Sync from ${getServerName()}" when online.`, 'warning')
        } else {
          updateStatus(`Ready! ${status.totalUsers} users loaded.`, 'success')
        }
      } catch (e) {
        console.error('Failed to initialize app:', e)
        updateStatus('Failed to initialize app', 'error')
      }
    })()

    window.addEventListener('online', updateOnline)
    window.addEventListener('offline', updateOnline)
    return () => {
      window.removeEventListener('online', updateOnline)
      window.removeEventListener('offline', updateOnline)
    }
  }, [])

  return (
    <div id="app">
      <nav className="navbar navbar-expand-lg navbar-dark bg-primary">
        <div className="container-fluid">
          <span className="navbar-brand">
            <i className="bi bi-clock-history me-2"></i>
            {APP_NAME}
          </span>
          <div className="d-flex align-items-center">
            <span className={`badge me-3 ${isOnline ? 'bg-success' : 'bg-warning'}`}>
              <i className={`bi ${isOnline ? 'bi-wifi' : 'bi-wifi-off'}`}></i>
              {" "}{isOnline ? 'Online' : 'Offline'}
            </span>
            <div className="btn-group">
              <button className="btn btn-outline-light btn-sm" onClick={syncActiveUsers} disabled={!isOnline || loading} title={`Sync active users from ${getServerName()}`}>
                <i className="bi bi-cloud-download me-1"></i>
                Sync from {getServerName()}
              </button>
              <button className="btn btn-outline-light btn-sm" onClick={syncAttendances} disabled={!isOnline || loading || unsyncedCount === 0} title={`Sync offline attendances to ${getServerName()}`}>
                <i className="bi bi-cloud-arrow-up me-1"></i>
                Sync to {getServerName()} ({unsyncedCount})
              </button>
            </div>
          </div>
        </div>
      </nav>

      <div className="container-fluid mt-4">
        <StatusBar status={status} type={statusType} loading={loading} />

        <div className="row">
          <div className="col-md-12">
            <StatsCards total={totalCount} pending={unsyncedCount} synced={syncedCount} users={users.length} />
          </div>
        </div>

        <div className="row">
          <div className="col-lg-4 col-md-6 mb-4">
            <Scanner currentUser={currentUser} loading={loading} onUserScanned={handleUserScanned} onRecordEntry={handleRecordEntry} onClearUser={clearUser} />
          </div>
          <div className="col-lg-8 col-md-6">
            <AttendanceTable attendances={attendances as any} loading={loading} onDeleteAttendance={deleteAttendance} onClearAllAttendances={clearAllAttendances} />
          </div>
        </div>
      </div>

      <ToastContainer />
    </div>
  )
}
