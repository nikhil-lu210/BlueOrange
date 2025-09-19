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
  const attendances = useAttendanceStore((state) => state.attendances);
  const unsyncedCount = useAttendanceStore((state) => state.unsyncedCount || 0);
  const totalCount = useAttendanceStore((state) => state.totalCount || 0);
  const syncedCount = useAttendanceStore((state) => state.syncedCount || 0);

  const users = useUsersStore((state) => state.users);

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
      await useAttendanceStore.getState().deleteAttendance(id);
      window.Toast?.show('Attendance record deleted', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to delete attendance record', 'error');
    }
  };

  const clearAllAttendances = async () => {
    try {
      await useAttendanceStore.getState().clearAll();
      window.Toast?.show('All attendance records cleared', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to clear attendance records', 'error');
    }
  };

  useEffect(() => {
    (async () => {
      try {
        const status = await workflowService.initializeApp();
        await useAttendanceStore.getState().loadAttendances();
        await useUsersStore.getState().loadUsers();

        if (navigator.onLine && !status.hasUsers) {
          await syncActiveUsers();
        }
      } catch (e) {
        console.error('Failed to initialize app:', e);
        window.Toast?.show('Failed to initialize app', 'error');
      }
    })();
  }, []);

  return (
    <div id="app">
      <NavBar
        isOnline={isOnline}
        loading={loading}
        unsyncedCount={unsyncedCount}
        onSyncActiveUsers={syncActiveUsers}
        onSyncAttendances={syncAttendances}
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
              users={users.length}
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
