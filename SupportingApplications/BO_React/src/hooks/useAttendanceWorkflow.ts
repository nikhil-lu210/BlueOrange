import { useState, useEffect } from 'react';
import { workflowService } from '../services/workflowService';
import type { User, AttendanceType, Status } from '../types';
import { getServerName } from '../utils/constants';
import { useStoreRefresh } from './useStoreRefresh';
import { useUsersStore } from '../stores/users';

export const useAttendanceWorkflow = () => {
  const { refreshAttendanceStore, refreshUsersStore } = useStoreRefresh();
  const [isOnline, setIsOnline] = useState<boolean>(typeof navigator !== 'undefined' ? navigator.onLine : true);
  const [loading, setLoading] = useState(false);
  const [status, setStatus] = useState<Status>({ message: 'Ready to scan attendance', type: 'info' });

  const updateStatus = (message: string, type: Status['type'] = 'info') => {
    setStatus({ message, type });
  };


  const handleRecordEntry = async (userid: string, type: AttendanceType) => {
    try {
      setLoading(true);
      updateStatus('Looking up user...', 'loading');

      // First, check if user exists in local database
      const user = await useUsersStore.getState().getUser(userid);
      if (!user) {
        updateStatus(`User ${userid} not found in local database. Please sync with ${getServerName()} first.`, 'error');
        window.Toast?.show(`User ${userid} not found. Please sync users first.`, 'error');
        return false;
      }

      updateStatus(`Recording ${type} entry for ${user.alias_name}...`, 'loading');
      const result = await workflowService.recordUserEntry(user.id, type);

      if (result.success) {
        updateStatus(result.message, 'success');
        // Refresh store state to trigger UI update
        await refreshAttendanceStore();
        window.Toast?.show('Entry recorded successfully', 'success');
        return true;
      } else {
        updateStatus(result.message, 'error');
        window.Toast?.show(result.message, 'error');
        return false;
      }
    } catch (e) {
      console.error(e);
      updateStatus('Failed to record entry', 'error');
      window.Toast?.show('Failed to record entry', 'error');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const syncActiveUsers = async () => {
      if (!isOnline) {
        updateStatus('No internet connection. Working offline.', 'warning');
        return;
      }
      try {
        setLoading(true);
        updateStatus(`Syncing active users from ${getServerName()}...`, 'loading');
        const result = await workflowService.syncActiveUsers((progress) => {
          updateStatus(progress.message, 'loading');
        });
        updateStatus(`Active users sync completed: ${result.totalUsers} users synced`, 'success');
        // Refresh store state to trigger UI update
        await refreshUsersStore();
        window.Toast?.show(`Synced ${result.totalUsers} active users from ${getServerName()}`, 'success');
        return result;
    } catch (e) {
      console.error(e);
      updateStatus('Active users sync failed', 'error');
      window.Toast?.show('Active users sync failed', 'error');
      return null;
    } finally {
      setLoading(false);
    }
  };

  const syncAttendances = async () => {
      if (!isOnline) {
        updateStatus('No internet connection', 'error');
        return;
      }
      try {
        setLoading(true);
        updateStatus(`Syncing offline attendances to ${getServerName()}...`, 'loading');
        const result = await workflowService.syncOfflineData();
        if (result.success) {
          updateStatus(`Synced ${result.syncedCount} records to ${getServerName()}`, 'success');
          // Refresh store state to trigger UI update
          await refreshAttendanceStore();
          window.Toast?.show(`Successfully synced ${result.syncedCount} records`, 'success');
          return result;
        } else {
          updateStatus('Sync failed', 'error');
          window.Toast?.show('Sync failed: ' + (result.message || ''), 'error');
          return null;
        }
    } catch (e) {
      console.error(e);
      updateStatus('Sync failed', 'error');
      window.Toast?.show('Sync failed', 'error');
      return null;
    } finally {
      setLoading(false);
    }
  };


  useEffect(() => {
    const updateOnline = () => {
      const online = navigator.onLine;
      setIsOnline(online);
      updateStatus(online ? 'Connected to server' : 'Working offline', online ? 'success' : 'warning');
    };

    window.addEventListener('online', updateOnline);
    window.addEventListener('offline', updateOnline);
    return () => {
      window.removeEventListener('online', updateOnline);
      window.removeEventListener('offline', updateOnline);
    };
  }, []);


  return {
    isOnline,
    loading,
    status,
    handleRecordEntry,
    syncActiveUsers,
    syncAttendances,
  };
};
