import { useCallback } from 'react';
import { useAttendanceStore } from '../stores/attendance';
import { useUsersStore } from '../stores/users';

/**
 * Custom hook that provides methods to refresh store states
 * This ensures UI updates automatically after operations
 */
export const useStoreRefresh = () => {
  const refreshAttendanceStore = useCallback(async () => {
    await useAttendanceStore.getState().loadAttendances();
  }, []);

  const refreshUsersStore = useCallback(async () => {
    await useUsersStore.getState().loadUsers();
  }, []);

  const refreshAllStores = useCallback(async () => {
    await Promise.all([
      refreshAttendanceStore(),
      refreshUsersStore()
    ]);
  }, [refreshAttendanceStore, refreshUsersStore]);

  return {
    refreshAttendanceStore,
    refreshUsersStore,
    refreshAllStores
  };
};
