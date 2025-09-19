import React from 'react';

interface DashboardStatsProps {
  total: number;
  pending: number;
  synced: number;
  users: number;
}

export const DashboardStats: React.FC<DashboardStatsProps> = ({
  total,
  pending,
  synced,
  users,
}) => {
  return (
    <div className="row">
      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-people"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Active Employees</div>
              <div className="h5 mb-0">{users}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-clock-history"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Today's Records</div>
              <div className="h5 mb-0">{total}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-exclamation-triangle"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Pending Upload</div>
              <div className="h5 mb-0">{pending}</div>
            </div>
          </div>
        </div>
      </div>

      <div className="col-lg-3 col-md-6 mb-3">
        <div className="stats-card p-3">
          <div className="d-flex align-items-center">
            <div className="flex-shrink-0">
              <div className="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style={{ width: 48, height: 48 }}>
                <i className="bi bi-check-circle"></i>
              </div>
            </div>
            <div className="flex-grow-1 ms-3">
              <div className="text-muted small">Uploaded</div>
              <div className="h5 mb-0">{synced}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
