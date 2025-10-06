export type ToastType = 'success' | 'error' | 'warning' | 'info';
export type AttendanceType = 'Regular' | 'Overtime';
export type StatusType = 'info' | 'success' | 'warning' | 'error' | 'loading';

export interface Attendance {
  id: number;
  user_id: number;
  entry_date_time: string;
  type: AttendanceType;
  synced: boolean;
  created_at: string;
}

export interface User {
  id: number;
  userid: string;
  name: string;
  alias_name: string;
  email?: string;
}

export interface Status {
  message: string;
  type: StatusType;
}

export interface SyncResult {
  success: boolean;
  message: string;
  syncedCount: number;
  totalCount: number;
  errors?: string[];
}

export interface WorkflowStatus {
  isInitialized: boolean;
  hasUsers: boolean;
  hasOpenAttendances: boolean;
  lastSyncTime: string | null;
  totalUsers: number;
  totalAttendances: number;
  unsyncedAttendances: number;
}
