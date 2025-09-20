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
  const stats = [
    {
      title: "Active Employees",
      value: users,
      icon: "bi-people-fill",
      color: "primary",
      bgColor: "bg-primary"
    },
    {
      title: "Today's Records",
      value: total,
      icon: "bi-clock-history",
      color: "info",
      bgColor: "bg-info"
    },
    {
      title: "Pending Upload",
      value: pending,
      icon: "bi-exclamation-triangle-fill",
      color: "warning",
      bgColor: "bg-warning"
    },
    {
      title: "Uploaded",
      value: synced,
      icon: "bi-check-circle-fill",
      color: "success",
      bgColor: "bg-success"
    }
  ];

  return (
    <div className="row g-3">
      {stats.map((stat, index) => (
        <div key={index} className="col-lg-3 col-md-6">
          <div className="card border-0 shadow-sm h-100 overflow-hidden position-relative">
            {/* Gradient Background */}
            <div className={`position-absolute top-0 start-0 w-100 ${stat.bgColor} bg-opacity-10`}
                 style={{ height: '4px' }}></div>

            <div className="card-body p-4">
              <div className="d-flex align-items-start justify-content-between">
                <div className="flex-grow-1">
                  <div className="h4 mb-1 fw-bold text-dark">{stat.value}</div>
                  <div className="text-muted small fw-medium text-uppercase tracking-wide">
                    {stat.title}
                  </div>
                </div>
                <div className={`${stat.bgColor} text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm`}
                     style={{ width: 48, height: 48 }}>
                  <i className={`bi ${stat.icon}`} style={{ fontSize: '1.2rem' }}></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
};

