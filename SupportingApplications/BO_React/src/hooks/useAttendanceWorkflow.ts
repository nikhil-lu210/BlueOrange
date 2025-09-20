import { useState, useEffect } from 'react';
import { workflowService } from '../services/workflowService';
import type { AttendanceType, Status } from '../types';
import { getServerName } from '../utils/constants';
import { useStoreRefresh } from './useStoreRefresh';
import { useUsersStore } from '../stores/users';

export const useAttendanceWorkflow = () => {
  const { refreshAttendanceStore, refreshUsersStore } = useStoreRefresh();
  const [isOnline, setIsOnline] = useState<boolean>(typeof navigator !== 'undefined' ? navigator.onLine : true);
  const [loading, setLoading] = useState(false);
  const [status, setStatus] = useState<Status>({ message: 'Ready to scan attendance', type: 'info' });

  // Function to get user name by ID for better error messages
  const getUserNameById = async (userId: string): Promise<string> => {
    try {
      const users = await useUsersStore.getState().getUserById(parseInt(userId));
      return users ? `${users.alias_name || users.name} (${users.userid})` : `User ID ${userId}`;
    } catch (e) {
      return `User ID ${userId}`;
    }
  };

  // Function to parse backend errors and convert them to user-friendly messages
  const parseSyncError = async (errorMessage: string, errors?: string[]): Promise<string> => {
    // Handle weekend clock-in error with user name
    if (errorMessage.includes('You cannot Regular Clock-In on Weekend')) {
      const userMatch = errorMessage.match(/attendance for (\d+):/);
      if (userMatch) {
        const userId = userMatch[1];
        const userName = await getUserNameById(userId);
        return `❌ ${userName}: Cannot clock in as Regular on weekends. Please use Overtime instead.`;
      }
      return '❌ Cannot clock in as Regular on weekends. Please use Overtime instead.';
    }

    // Handle holiday clock-in error with user name
    if (errorMessage.includes('You cannot Regular Clock-In on Holiday')) {
      const userMatch = errorMessage.match(/attendance for (\d+):/);
      if (userMatch) {
        const userId = userMatch[1];
        const userName = await getUserNameById(userId);
        return `❌ ${userName}: Cannot clock in as Regular on holidays. Please use Overtime instead.`;
      }
      return '❌ Cannot clock in as Regular on holidays. Please use Overtime instead.';
    }

    // Handle duplicate entry error with user name
    if (errorMessage.includes('Duplicate entry') || errorMessage.includes('Integrity constraint violation')) {
      const userMatch = errorMessage.match(/attendance for (\d+):/);
      if (userMatch) {
        const userId = userMatch[1];
        const userName = await getUserNameById(userId);
        return `⚠️ ${userName}: This attendance record was already uploaded previously.`;
      }
      return '⚠️ This attendance record was already uploaded previously.';
    }

    // Handle general sync errors with specific user details
    if (errorMessage.includes('Synced') && errorMessage.includes('of') && errorMessage.includes('attendance records')) {
      const match = errorMessage.match(/Synced (\d+) of (\d+) attendance records/);
      if (match) {
        const synced = parseInt(match[1]);
        const total = parseInt(match[2]);
        const failed = total - synced;

        // Try to extract specific error details
        const errorDetails: string[] = [];

        // First, try to parse from the errors array (more reliable)
        if (errors && errors.length > 0) {
          for (const error of errors) {
            const userMatch = error.match(/attendance for (\d+): (.+)/);
            if (userMatch) {
              const userId = userMatch[1];
              const reason = userMatch[2];
              const userName = await getUserNameById(userId);
              if (reason.includes('Weekend')) {
                errorDetails.push(`• ${userName}: Cannot clock in as Regular on weekends. Please use Overtime instead.`);
              } else if (reason.includes('Holiday')) {
                errorDetails.push(`• ${userName}: Cannot clock in as Regular on holidays. Please use Overtime instead.`);
              } else if (reason.includes('Duplicate')) {
                errorDetails.push(`• ${userName}: This record was already uploaded previously.`);
              } else {
                errorDetails.push(`• ${userName}: ${reason}`);
              }
            }
          }
        }

        // Fallback: try to parse from the error message itself
        if (errorDetails.length === 0) {
          const errorMatches = errorMessage.match(/attendance for (\d+): ([^"]+)/g);
          if (errorMatches) {
            for (const error of errorMatches) {
              const userMatch = error.match(/attendance for (\d+): (.+)/);
              if (userMatch) {
                const userId = userMatch[1];
                const reason = userMatch[2];
                const userName = await getUserNameById(userId);
                if (reason.includes('Weekend')) {
                  errorDetails.push(`• ${userName}: Cannot clock in as Regular on weekends. Please use Overtime instead.`);
                } else if (reason.includes('Holiday')) {
                  errorDetails.push(`• ${userName}: Cannot clock in as Regular on holidays. Please use Overtime instead.`);
                } else if (reason.includes('Duplicate')) {
                  errorDetails.push(`• ${userName}: This record was already uploaded previously.`);
                } else {
                  errorDetails.push(`• ${userName}: ${reason}`);
                }
              }
            }
          }
        }

        // Create user-friendly messages
        if (synced > 0 && failed > 0) {
          // Partial success case
          let message = `✅ ${synced} attendance record${synced > 1 ? 's' : ''} uploaded successfully.\n\n❌ ${failed} record${failed > 1 ? 's' : ''} could not be uploaded:`;
          if (errorDetails.length > 0) {
            message += `\n\n${errorDetails.join('\n')}`;
          }
          return message;
        } else if (synced === 0 && failed > 0) {
          // Complete failure case
          let message = `❌ None of the attendance records could be uploaded:`;
          if (errorDetails.length > 0) {
            message += `\n\n${errorDetails.join('\n')}`;
          } else {
            // Fallback message when we can't parse specific errors
            message += `\n\n• Please check if any records violate business rules:\n`;
            message += `  - Regular clock-in is not allowed on weekends or holidays\n`;
            message += `  - Use Overtime instead for weekend/holiday attendance\n`;
            message += `  - Avoid duplicate records (same user, same day, same type)`;
          }
          return message;
        }

        return errorMessage;
      }
    }

    // Default fallback - try to extract meaningful part
    if (errorMessage.includes('HTTP 422:')) {
      return '❌ Some attendance records could not be uploaded. Please check if they follow business rules (e.g., no Regular clock-in on weekends).';
    }

    return errorMessage;
  };

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

      updateStatus(`Recording ${type} entry for ${user.name}...`, 'loading');
      const result = await workflowService.recordUserEntry(user.id, type);

      if (result.success) {
        updateStatus(result.message, 'success');
        // Refresh store state to trigger UI update
        await refreshAttendanceStore();
        window.Toast?.show('Entry recorded successfully', 'success');
        return true;
      } else {
        const userFriendlyMessage = await parseSyncError(result.message || 'Failed to record entry');
        updateStatus(userFriendlyMessage, 'error');
        window.Toast?.show(userFriendlyMessage, 'error');
        return false;
      }
    } catch (e) {
      console.error(e);
      const errorMessage = e instanceof Error ? e.message : 'Unknown error occurred';
      const userFriendlyMessage = await parseSyncError(errorMessage);
      updateStatus(userFriendlyMessage, 'error');
      window.Toast?.show(userFriendlyMessage, 'error');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const syncActiveUsers = async () => {
    if (!isOnline) {
      updateStatus('No internet connection. Working offline.', 'warning');
      return null;
    }
    try {
      setLoading(true);
      updateStatus(`Syncing active users from ${getServerName()}...`, 'loading');
      const result = await workflowService.syncActiveUsers((progress) => {
        updateStatus(progress.message, 'loading');
      });

      // The workflowService returns a WorkflowStatus, not a SyncResult
      // It doesn't have a 'success' property, so we check if it has users
      if (result.totalUsers > 0) {
        updateStatus(`Active users sync completed: ${result.totalUsers} users synced`, 'success');
        // Refresh store state to trigger UI update
        await refreshUsersStore();
        window.Toast?.show(`Synced ${result.totalUsers} active users from ${getServerName()}`, 'success');
        return result;
      } else {
        updateStatus('No users were synced', 'error');
        window.Toast?.show('No users were synced', 'error');
        return null;
      }
    } catch (e) {
      console.error('Error in syncActiveUsers:', e);
      const errorMessage = e instanceof Error ? e.message : 'Unknown error occurred';
      const userFriendlyMessage = await parseSyncError(errorMessage);
      updateStatus('Active users sync failed', 'error');
      window.Toast?.show(userFriendlyMessage, 'error');
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
          // Complete success
          updateStatus(`Synced ${result.syncedCount} records to ${getServerName()}`, 'success');
          await refreshAttendanceStore();
          window.Toast?.show(`Successfully synced ${result.syncedCount} records`, 'success');
          return result;
        } else if (result.syncedCount > 0) {
          // Partial success - some records synced, some failed
          const userFriendlyMessage = await parseSyncError(result.message || 'Sync failed', result.errors);
          updateStatus(`Partially synced ${result.syncedCount} records`, 'warning');
          await refreshAttendanceStore();
          window.Toast?.show(userFriendlyMessage, 'warning');
          return result;
        } else {
          // Complete failure
          const userFriendlyMessage = await parseSyncError(result.message || 'Sync failed', result.errors);
          updateStatus('Sync failed', 'error');
          window.Toast?.show(userFriendlyMessage, 'error');
          return null;
        }
    } catch (e) {
      console.error(e);
      const errorMessage = e instanceof Error ? e.message : 'Unknown error occurred';
      const userFriendlyMessage = await parseSyncError(errorMessage);
      updateStatus('Sync failed', 'error');
      window.Toast?.show(userFriendlyMessage, 'error');
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
