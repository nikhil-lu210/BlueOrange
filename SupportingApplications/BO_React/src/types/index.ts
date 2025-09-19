export type ToastType = 'success' | 'error' | 'warning' | 'info';
export type AttendanceType = 'Regular' | 'Overtime';

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