import React, { useEffect, useState, useRef } from 'react';
import { smartDbService as dbService } from '../services/smartDb';
import type { Attendance, User } from '../types';
import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-bs5';
import 'datatables.net-responsive';

interface AttendanceRecordsProps {
  attendances: Attendance[];
  loading: boolean;
  onDeleteAttendance: (id: number) => void;
}

export const AttendanceRecords: React.FC<AttendanceRecordsProps> = ({
  attendances,
  loading,
  onDeleteAttendance,
}) => {
  const [users, setUsers] = useState<User[]>([]);
  const tableRef = useRef<HTMLTableElement>(null);
  const dataTableRef = useRef<any>(null);

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

  // Initialize DataTable when attendances change
  useEffect(() => {
    if (tableRef.current && attendances.length > 0) {
      // Destroy existing DataTable if it exists
      if (dataTableRef.current) {
        dataTableRef.current.destroy();
        dataTableRef.current = null;
      }

      // Initialize new DataTable
      dataTableRef.current = $(tableRef.current).DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        order: [[2, 'desc']], // Sort by time column (descending)
        columnDefs: [
          { orderable: false, targets: 4 }, // Disable ordering on Action column
          { className: 'text-center', targets: 4 } // Center align Action column
        ],
        language: {
          search: "Search records:",
          lengthMenu: "Show _MENU_ records per page",
          info: "Showing _START_ to _END_ of _TOTAL_ records",
          infoEmpty: "Showing 0 to 0 of 0 records",
          infoFiltered: "(filtered from _MAX_ total records)",
          paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
          }
        }
      });
    }

    return () => {
      if (dataTableRef.current) {
        dataTableRef.current.destroy();
        dataTableRef.current = null;
      }
    };
  }, [attendances]);

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
    <div className="card border-0 shadow-sm">
      <div className="card-header bg-white border-0 py-4 position-relative">
        {/* Top accent line */}
        <div className="position-absolute top-0 start-0 w-100 bg-primary" style={{ height: '4px' }}></div>

        <div className="d-flex align-items-center justify-content-between">
          <div className="d-flex align-items-center">
            <div className="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-3"
                 style={{ width: 40, height: 40 }}>
              <i className="bi bi-list-check" style={{ fontSize: '1.1rem' }}></i>
            </div>
            <div>
              <h6 className="mb-0 fw-bold text-dark">Today's Attendance Records</h6>
              <small className="text-muted">Real-time attendance tracking</small>
            </div>
          </div>
          <div className="d-flex align-items-center gap-2">
            {loading && (
              <div className="spinner-border spinner-border-sm text-primary" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
            )}
            <div className="bg-primary text-white rounded-pill px-3 py-1">
              <span className="fw-semibold small">{attendances.length}</span>
            </div>
          </div>
        </div>
      </div>
      <div className="card-body p-0">

        {attendances.length === 0 ? (
          <div className="text-center text-muted py-5">
            <div className="mb-4">
              <div className="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                   style={{ width: 80, height: 80 }}>
                <i className="bi bi-clock-history text-muted" style={{ fontSize: '2rem', opacity: 0.5 }}></i>
              </div>
            </div>
            <h6 className="text-muted mb-2 fw-semibold">No attendance records for today</h6>
            <p className="text-muted small mb-0">Scan an employee barcode to start recording attendance</p>
          </div>
        ) : (
          <div className="p-3">
            <div className="table-responsive">
              <table ref={tableRef} className="table table-bordered table-striped mb-0">
                <thead className="table-light">
                  <tr>
                    <th className="text-center" style={{ width: '25%' }}>Employee</th>
                    <th className="text-center" style={{ width: '15%' }}>Type</th>
                    <th className="text-center" style={{ width: '20%' }}>Time</th>
                    <th className="text-center" style={{ width: '15%' }}>Status</th>
                    <th className="text-center" style={{ width: '25%' }}>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {attendances.map((attendance) => (
                    <tr key={attendance.id}>
                      <td className="py-2">
                        <div className="d-flex align-items-center">
                          <div className="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style={{ width: 32, height: 32 }}>
                            <i className="bi bi-person-fill" style={{ fontSize: '0.8rem' }}></i>
                          </div>
                          <div>
                            <div className="fw-semibold text-dark" style={{ fontSize: '0.9rem' }}>
                              {getUserInfo(attendance.user_id)?.alias_name || 'Unknown User'}
                            </div>
                            <small className="text-muted" style={{ fontSize: '0.75rem' }}>
                              {getUserInfo(attendance.user_id)?.userid || `ID: ${attendance.user_id}`}
                            </small>
                          </div>
                        </div>
                      </td>
                      <td className="text-center py-2">
                        <span className={`badge ${attendance.type === 'Overtime' ? 'bg-label-warning' : 'bg-label-primary'} px-2 py-1 rounded-pill`} style={{ fontSize: '0.75rem' }}>
                          <i className={`bi ${attendance.type === 'Overtime' ? 'bi-moon-fill' : 'bi-clock-fill'} me-1`}></i>
                          {attendance.type || 'Regular'}
                        </span>
                      </td>
                      <td className="text-center py-2">
                        <div>
                          <div className="fw-medium text-dark" style={{ fontSize: '0.85rem' }}>{formatDate(attendance.entry_date_time)}</div>
                          <small className="text-muted" style={{ fontSize: '0.75rem' }}>{formatTime(attendance.entry_date_time)}</small>
                        </div>
                      </td>
                      <td className="text-center py-2">
                        <span className={`badge ${attendance.synced ? 'bg-label-success' : 'bg-label-warning'} px-2 py-1 rounded-pill`} style={{ fontSize: '0.75rem' }}>
                          <i className={`bi ${attendance.synced ? 'bi-check-circle-fill' : 'bi-clock-history'} me-1`}></i>
                          {attendance.synced ? 'Synced' : 'Pending'}
                        </span>
                      </td>
                      <td className="text-center py-2">
                        <button
                          className="btn btn-outline-danger btn-sm px-2 py-1"
                          onClick={() => {
                            if (!loading && confirm('Are you sure you want to delete this attendance record?'))
                              onDeleteAttendance(attendance.id);
                          }}
                          disabled={loading}
                          title="Delete record"
                          style={{ fontSize: '0.75rem' }}
                        >
                          <i className="bi bi-trash me-1"></i>Delete
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
