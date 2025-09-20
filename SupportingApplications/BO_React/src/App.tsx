import { useEffect } from 'react';
import './App.css';
import './assets/css/app.css';
import { NavBar } from './components/NavBar';
import { StatusBar } from './components/StatusBar';
import { DashboardStats } from './components/DashboardStats';
import { BarcodeScanner } from './components/BarcodeScanner';
import { AttendanceRecords } from './components/AttendanceRecords';
import { ToastContainer } from './components/Toast';
import { AuthorizationModal } from './components/AuthorizationModal';
import { useAttendanceStore } from './stores/attendance';
import { useUsersStore } from './stores/users';
import { useAttendanceWorkflow } from './hooks/useAttendanceWorkflow';
import { useAuthorization } from './hooks/useAuthorization';
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
    loading,
    status,
    handleRecordEntry,
    syncActiveUsers,
    syncAttendances,
  } = useAttendanceWorkflow();

  const {
    isModalOpen,
    modalTitle,
    modalMessage,
    authorizedUser,
    loading: authLoading,
    authorizeUser,
    requestAuthorization,
    clearAuthorization,
    closeModal,
  } = useAuthorization();

  const deleteAttendance = async (id: number) => {
    try {
      await useAttendanceStore.getState().deleteAttendance(id);
      // Store state is already refreshed by deleteAttendance method
      window.Toast?.show('Attendance record deleted', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to delete attendance record', 'error');
    }
  };

  const clearAllAttendances = async () => {
    try {
      await useAttendanceStore.getState().clearAll();
      // Store state is already refreshed by clearAll method
      window.Toast?.show('All attendance records cleared', 'success');
    } catch (e) {
      console.error(e);
      window.Toast?.show('Failed to clear attendance records', 'error');
    }
  };

  // Authorized versions of sensitive operations
  const handleSyncActiveUsers = () => {
    requestAuthorization(
      'Sync Active Users',
      'You need to authorize to sync active users from the server. This will download all active users to your local database.'
    );
  };

  const handleSyncAttendances = () => {
    requestAuthorization(
      'Sync Attendance Records',
      'You need to authorize to sync offline attendance records to the server. This will upload all pending attendance records.'
    );
  };

  const handleClearAll = () => {
    requestAuthorization(
      'Clear All Records',
      'You need to authorize to clear all attendance records. This action cannot be undone and will permanently delete all local attendance data.'
    );
  };

  // Execute operations after authorization
  useEffect(() => {
    if (authorizedUser) {
      // Check which operation was requested based on modal title
      if (modalTitle === 'Sync Active Users') {
        syncActiveUsers().finally(() => clearAuthorization());
      } else if (modalTitle === 'Sync Attendance Records') {
        syncAttendances().finally(() => clearAuthorization());
      } else if (modalTitle === 'Clear All Records') {
        clearAllAttendances().finally(() => clearAuthorization());
      }
    }
  }, [authorizedUser, modalTitle]); // Remove function dependencies to prevent infinite loop

  useEffect(() => {
    (async () => {
      try {
        await workflowService.initializeApp();
        await useAttendanceStore.getState().loadAttendances();
        await useUsersStore.getState().loadUsers();

        // Don't automatically sync users - let user do it manually with authorization
        // if (navigator.onLine && !status.hasUsers) {
        //   await syncActiveUsers();
        // }
      } catch (e) {
        console.error('Failed to initialize app:', e);
        window.Toast?.show('Failed to initialize app', 'error');
      }
    })();
  }, []); // Remove syncActiveUsers dependency to prevent infinite loop

  return (
    <div id="app">
            <NavBar
              isOnline={isOnline}
              loading={loading}
              unsyncedCount={unsyncedCount}
              onSyncActiveUsers={handleSyncActiveUsers}
              onSyncAttendances={handleSyncAttendances}
              onClearAll={handleClearAll}
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
              loading={loading}
              onRecordEntry={handleRecordEntry}
            />
          </div>
          <div className="col-lg-8 col-md-6">
              <AttendanceRecords
                attendances={attendances}
                loading={loading}
                onDeleteAttendance={deleteAttendance}
              />
          </div>
        </div>
      </div>

            <ToastContainer />

            <AuthorizationModal
              isOpen={isModalOpen}
              onClose={closeModal}
              onAuthorize={authorizeUser}
              title={modalTitle}
              message={modalMessage}
              loading={authLoading}
            />
          </div>
        );
      }
