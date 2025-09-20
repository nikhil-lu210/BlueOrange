import React from 'react';
import { getServerName, APP_NAME } from '../utils/constants';

interface NavBarProps {
  isOnline: boolean;
  loading: boolean;
  unsyncedCount: number;
  onSyncActiveUsers: () => void;
  onSyncAttendances: () => void;
  onClearAll: () => void;
}

export const NavBar: React.FC<NavBarProps> = ({
  isOnline,
  loading,
  unsyncedCount,
  onSyncActiveUsers,
  onSyncAttendances,
  onClearAll,
}) => {
  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-white border-bottom">
      <div className="container-fluid px-4">
        <div className="navbar-brand d-flex align-items-center">
          <div className="bg-primary rounded-2 d-flex align-items-center justify-content-center me-3" style={{ width: 36, height: 36 }}>
            <i className="bi bi-clock-fill text-white"></i>
          </div>
          <div>
            <div className="fw-bold text-dark mb-0">{APP_NAME}</div>
            <small className="text-muted">Attendance Management</small>
          </div>
        </div>

        <div className="d-flex align-items-center gap-3">
          {/* Connection Status */}
          <div className="d-flex align-items-center">
            <div className={`rounded-circle me-2 ${isOnline ? 'bg-success' : 'bg-warning'}`} style={{ width: 8, height: 8 }}></div>
            <span className="text-muted small">{isOnline ? 'Online' : 'Offline'}</span>
          </div>

          {/* Action Buttons */}
          <div className="d-flex gap-2">
            <button
              className="btn btn-label-primary btn-sm"
              onClick={onSyncActiveUsers}
              disabled={!isOnline || loading}
              title={`Sync active users from ${getServerName()}`}
            >
              <i className="bi bi-cloud-download"></i>
            </button>
            <button
              className="btn btn-label-success btn-sm position-relative"
              onClick={onSyncAttendances}
              disabled={!isOnline || loading || unsyncedCount === 0}
              title={`Sync offline attendances to ${getServerName()}`}
            >
              <i className="bi bi-cloud-arrow-up"></i>
              {unsyncedCount > 0 && (
                <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {unsyncedCount}
                </span>
              )}
            </button>
            <button
              className="btn btn-label-danger btn-sm"
              onClick={onClearAll}
              disabled={loading}
              title="Clear all attendance records"
            >
              <i className="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </nav>
  );
};
