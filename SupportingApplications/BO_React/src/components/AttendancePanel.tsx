import React, { useEffect, useState } from 'react';
import { smartDbService as dbService } from '../bo/services/smartDb';
import type { Attendance, User } from '../types';

interface AttendancePanelProps {
  attendances: Attendance[];
  loading: boolean;
  onDeleteAttendance: (id: number) => void;
  onClearAllAttendances: () => void;
}

export const AttendancePanel: React.FC<AttendancePanelProps> = ({
  attendances,
  loading,
  onDeleteAttendance,
  onClearAllAttendances,
}) => {
  const [users, setUsers] = useState<User[]>([]);

  useEffect(() => {
    let mounted = true;
    (async () => {
      try {
        const u = await dbService.getAllUsers();
        if (mounted) setUsers(u);
      } catch (e) {
        console.error('Failed to load users', e);
      }
    })();
    return () => { mounted = false; };
  }, []);

  const getUserInfo = (userId: number): User | undefined => 
    users.find(u => u.id === userId);

  const formatDate = (dateString: string): string => {
    try { 
      return new Date(dateString).toLocaleDateString(); 
    } catch { 
      return 'Invalid Date'; 
    }
  };

  const formatTime = (timeString: string): string => {
    try {
      const date = new Date(timeString);
      if (isNaN(date.getTime())) return timeString;
      return date.toLocaleTimeString();
    } catch { 
      return 'Invalid Time'; 
    }
  };

  return (
    <div className="attendance-panel p-4">
      <div className="d-flex align-items-center justify-content-between mb-3">
        <h5 className="mb-0">
          <i className="bi bi-table me-2"></i>
          Attendance Records
        </h5>
        <div className="d-flex align-items-center gap-2">
          {attendances.length > 0 && (
            <button 
              className="btn btn-outline-danger btn-sm" 
              onClick={() => !loading && onClearAllAttendances()} 
              disabled={loading} 
              title="Clear all attendance records"
            >
              <i className="bi bi-trash"></i>
              {" "}Clear All
            </button>
          )}
          {loading && <div className="loading-spinner"></div>}
        </div>
      </div>

      {attendances.length === 0 ? (
        <div className="text-center text-muted py-4">
          <i className="bi bi-inbox display-4 d-block mb-2"></i>
          <p className="mb-0">No attendance records found</p>
          <small>Scan a barcode to start recording attendance</small>
        </div>
      ) : (
        <div className="table-responsive">
          <table className="table table-attendance">
            <thead>
              <tr>
                <th>User</th>
                <th>Type</th>
                <th>Entry Time</th>
                <th>Synced</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {attendances.map((attendance) => (
                <tr key={attendance.id}>
                  <td>
                    <div>
                      <div className="fw-medium">
                        {getUserInfo(attendance.user_id)?.alias_name || 'Unknown User'}
                      </div>
                      <small className="text-muted">
                        {getUserInfo(attendance.user_id)?.userid || `ID: ${attendance.user_id}`}
                      </small>
                    </div>
                  </td>
                  <td>
                    <span className={`badge ${attendance.type === 'Overtime' ? 'bg-warning' : 'bg-primary'}`}>
                      {attendance.type || 'Regular'}
                    </span>
                  </td>
                  <td>
                    <div>
                      <div>{formatDate(attendance.entry_date_time)}</div>
                      <small className="text-muted">{formatTime(attendance.entry_date_time)}</small>
                    </div>
                  </td>
                  <td>
                    <span className={`badge ${attendance.synced ? 'badge-synced' : 'badge-pending'}`}>
                      <i className={`bi ${attendance.synced ? 'bi-check-circle' : 'bi-clock-history'}`}></i>
                      {" "}{attendance.synced ? 'Synced' : 'Pending'}
                    </span>
                  </td>
                  <td>
                    <button 
                      className="btn btn-outline-danger btn-sm" 
                      onClick={() => { 
                        if (!loading && confirm('Are you sure you want to delete this attendance record?')) 
                          onDeleteAttendance(attendance.id); 
                      }} 
                      disabled={loading} 
                      title="Delete record"
                    >
                      <i className="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};