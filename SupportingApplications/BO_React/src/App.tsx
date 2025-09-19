import { useEffect } from 'react';
import './App.css';
import './assets/css/app.css';
import { NavBar } from './components/NavBar';
import { StatusBar } from './components/StatusBar';
import { DashboardStats } from './components/DashboardStats';
import { BarcodeScanner } from './components/BarcodeScanner';
import { AttendanceRecords } from './components/AttendanceRecords';
import { ToastContainer } from './components/Toast';
import { useAttendanceStore } from './stores/attendance';
import { useUsersStore } from './stores/users';
import { useAttendanceWorkflow } from './hooks/useAttendanceWorkflow';
import { workflowService } from './services/workflowService';

export default function App() {
  // Use proper Zustand selectors for reactive updates
  const attendanceStore = useAttendanceStore();
  const usersStore = useUsersStore();

  const attendances = attendanceStore.attendances;
  const unsyncedCount = attendanceStore.unsyncedCount || 0;
  const totalCount = attendanceStore.totalCount || 0;
  const syncedCount = attendanceStore.syncedCount || 0;

  const {
    isOnline,
    currentUser,
    loading,
    status,
    handleUserScanned,
    handleRecordEntry,
    syncActiveUsers,
    syncAttendances,
    clearUser,
  } = useAttendanceWorkflow();

  const deleteAttendance = async (id: number) => {
    try {
      await attendanceStore.deleteAttendance(id);
      await reloadData();
      window.Toast?.show('Attendance record deleted', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to delete attendance record', 'error');
    }
  };

  const clearAllAttendances = async () => {
    try {
      await attendanceStore.clearAll();
      window.Toast?.show('All attendance records cleared', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to clear attendance records', 'error');
    }
  };

  const reloadData = async () => {
    await Promise.all([
      attendanceStore.loadAttendances(),
      usersStore.loadUsers()
    ]);
  };

  useEffect(() => {
    (async () => {
      try {
        const status = await workflowService.initializeApp();
        await reloadData();

        if (navigator.onLine && !status.hasUsers) {
          await syncActiveUsers();
        }
      } catch (e) {
        console.error('Failed to initialize app:', e);
        window.Toast?.show('Failed to initialize app', 'error');
      }
    })();
  }, []);

  // Monitor unsyncedCount changes to show sync notification
  useEffect(() => {
    if (unsyncedCount > 0 && isOnline) {
      window.Toast?.show('You have unsynced attendance records. Click "Sync Attendances" to upload them.', 'info');
    }
  }, [unsyncedCount, isOnline]);

  return (
    <div id="app">
      <NavBar
        isOnline={isOnline}
        loading={loading}
        unsyncedCount={unsyncedCount}
        onSyncActiveUsers={async () => {
          const result = await syncActiveUsers();
          if (result) await reloadData();
        }}
        onSyncAttendances={async () => {
          const result = await syncAttendances();
          if (result) await reloadData();
        }}
      />

      <div className="container-fluid mt-4">
        <StatusBar
          status={status.message}
          type={status.type}
          loading={loading}
        />

        <div className="row">
          <div className="col-md-12">
            <DashboardStats
              total={totalCount}
              pending={unsyncedCount}
              synced={syncedCount}
              users={usersStore.users.length}
            />
          </div>
        </div>

        <div className="row">
          <div className="col-lg-4 col-md-6 mb-4">
            <BarcodeScanner
              currentUser={currentUser}
              loading={loading}
              onUserScanned={handleUserScanned}
              onRecordEntry={handleRecordEntry}
              onClearUser={clearUser}
            />
          </div>
          <div className="col-lg-8 col-md-6">
            <AttendanceRecords
              attendances={attendances}
              loading={loading}
              onDeleteAttendance={deleteAttendance}
              onClearAllAttendances={clearAllAttendances}
            />
          </div>
        </div>
      </div>

      <ToastContainer />
    </div>
  );
}
