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
  const getStatusBg = (type: StatusBarProps['type']) => {
    const classes = {
      info: 'bg-info bg-opacity-10 border-info',
      success: 'bg-success bg-opacity-10 border-success',
      warning: 'bg-warning bg-opacity-10 border-warning',
      error: 'bg-danger bg-opacity-10 border-danger',
      loading: 'bg-primary bg-opacity-10 border-primary'
    };
    return classes[type || 'info'];
  };

  return (
    <div className={`alert ${getStatusBg(type)} border-0 mb-0 py-2`}>
      <div className="d-flex align-items-center justify-content-between">
        <div className="d-flex align-items-center">
          <div className={`rounded-circle d-flex align-items-center justify-content-center me-3 ${getStatusClass(type)}`}
               style={{ width: 24, height: 24, backgroundColor: 'currentColor', opacity: 0.1 }}>
            <i className={`${getStatusIcon(type)} ${getStatusClass(type)}`} style={{ fontSize: '0.8rem' }}></i>
          </div>
          <span className="fw-medium text-dark">{status}</span>
        </div>
        {loading && (
          <div className="spinner-border spinner-border-sm text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
        )}
      </div>
    </div>
  );
};
