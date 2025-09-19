import React from 'react';

interface StatusBarProps {
  status: string;
  type?: 'info' | 'success' | 'warning' | 'error' | 'loading';
  loading?: boolean;
}

const getStatusIcon = (type: StatusBarProps['type']) => {
  const icons = {
    info: 'bi-info-circle',
    success: 'bi-check-circle',
    warning: 'bi-exclamation-triangle',
    error: 'bi-exclamation-circle',
    loading: 'bi-arrow-repeat'
  };
  return `bi ${icons[type || 'info']}`;
};

const getStatusClass = (type: StatusBarProps['type']) => {
  const classes = {
    info: 'text-info',
    success: 'text-success',
    warning: 'text-warning',
    error: 'text-danger',
    loading: 'text-primary'
  };
  return classes[type || 'info'];
};

export const StatusBar: React.FC<StatusBarProps> = ({ 
  status, 
  type = 'info', 
  loading = false 
}) => {
  return (
    <div className="status-bar">
      <div className="d-flex align-items-center justify-content-between">
        <div className="d-flex align-items-center">
          <i className={`${getStatusIcon(type)} ${getStatusClass(type)} me-2`}></i>
          <span className="fw-medium">{status}</span>
        </div>
        {loading && <div className="loading-spinner"></div>}
      </div>
    </div>
  );
};