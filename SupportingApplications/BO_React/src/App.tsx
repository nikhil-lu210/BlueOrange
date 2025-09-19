import { useEffect } from 'react';
import './App.css';
import './assets/css/app.css';
import { NavBar } from './components/NavBar';
import { StatusBar } from './components/StatusBar';
import { StatsCards } from './components/StatsCards';
import { ScannerPanel } from './components/ScannerPanel';
import { AttendancePanel } from './components/AttendancePanel';
import { ToastContainer } from './components/Toast';
import { useAttendanceStore } from './bo/stores/attendance';
import { useUsersStore } from './bo/stores/users';
import { useAttendanceWorkflow } from './hooks/useAttendanceWorkflow';
import { workflowService } from './bo/services/workflowService';

export default function App() {
  const attendanceStore = useAttendanceStore();
  const usersStore = useUsersStore();
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

  const attendances = attendanceStore.attendances;
  const users = usersStore.users;
  const unsyncedCount = attendanceStore.unsyncedCount || 0;
  const totalCount = attendanceStore.totalCount || 0;
  const syncedCount = attendanceStore.syncedCount || 0;

  const deleteAttendance = async (id: number) => {
    try {
      await attendanceStore.deleteAttendance(id);
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

  useEffect(() => {
    (async () => {
      try {
        const status = await workflowService.initializeApp();
        await attendanceStore.loadAttendances();
        await usersStore.loadUsers();

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
            <StatsCards
              total={totalCount}
              pending={unsyncedCount}
              synced={syncedCount}
              users={users.length}
            />
          </div>
        </div>

        <div className="row">
          <div className="col-lg-4 col-md-6 mb-4">
            <ScannerPanel
              currentUser={currentUser}
              loading={loading}
              onUserScanned={handleUserScanned}
              onRecordEntry={handleRecordEntry}
              onClearUser={clearUser}
            />
          </div>
          <div className="col-lg-8 col-md-6">
            <AttendancePanel
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
