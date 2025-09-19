import React from 'react';
import { getServerName, APP_NAME } from '../utils/constants';

interface NavBarProps {
  isOnline: boolean;
  loading: boolean;
  unsyncedCount: number;
  onSyncActiveUsers: () => void;
  onSyncAttendances: () => void;
}

export const NavBar: React.FC<NavBarProps> = ({
  isOnline,
  loading,
  unsyncedCount,
  onSyncActiveUsers,
  onSyncAttendances,
}) => {
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-primary">
      <div className="container-fluid">
        <span className="navbar-brand">
          <i className="bi bi-clock-history me-2"></i>
          {APP_NAME}
        </span>
        <div className="d-flex align-items-center">
          <span className={`badge me-3 ${isOnline ? 'bg-success' : 'bg-warning'}`}>
            <i className={`bi ${isOnline ? 'bi-wifi' : 'bi-wifi-off'}`}></i>
            {" "}{isOnline ? 'Online' : 'Offline'}
          </span>
          <div className="btn-group">
            <button
              className="btn btn-outline-light btn-sm"
              onClick={onSyncActiveUsers}
              disabled={!isOnline || loading}
              title={`Sync active users from ${getServerName()}`}
            >
              <i className="bi bi-cloud-download me-1"></i>
              Sync from {getServerName()}
            </button>
            <button
              className="btn btn-outline-light btn-sm"
              onClick={onSyncAttendances}
              disabled={!isOnline || loading || unsyncedCount === 0}
              title={`Sync offline attendances to ${getServerName()}`}
            >
              <i className="bi bi-cloud-arrow-up me-1"></i>
              Sync to {getServerName()} ({unsyncedCount})
            </button>
          </div>
        </div>
      </div>
    </nav>
  );
};
