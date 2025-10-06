import React, { useEffect, useRef, useState } from 'react';
import type { ToastType } from '../../types';
import type { ToastItem } from './types';

export const ToastContainer: React.FC = () => {
  const [toasts, setToasts] = useState<ToastItem[]>([]);
  const nextIdRef = useRef(1);

  const removeToast = (id: number) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  };

  useEffect(() => {
    const show = (message: string, type: ToastType = 'info') => {
      const id = nextIdRef.current++;
      const toast = { id, type, message };
      setToasts((prev) => [...prev, toast]);

      // Auto-close timing: 15 seconds for errors/warnings, 5 seconds for success/info
      const timeout = (type === 'error' || type === 'warning') ? 15000 : 5000;
      window.setTimeout(() => removeToast(id), timeout);
    };

    window.Toast = { show };

    return () => { window.Toast = undefined; };
  }, []);

  const getIcon = (type: ToastType) => {
    const icons: Record<ToastType, string> = {
      success: 'bi-check-circle-fill text-success',
      error: 'bi-exclamation-triangle-fill text-danger',
      warning: 'bi-exclamation-triangle-fill text-warning',
      info: 'bi-info-circle-fill text-info'
    };
    return icons[type];
  };

  const getTitle = (type: ToastType) => {
    const titles: Record<ToastType, string> = {
      success: 'Success',
      error: 'Error',
      warning: 'Warning',
      info: 'Info'
    };
    return titles[type];
  };

  return (
    <div className="toast-container">
      {toasts.map((toast) => (
        <div key={toast.id} className={`toast show toast-${toast.type}`} role="alert">
          <div className="toast-header">
            <i className={`bi me-2 ${getIcon(toast.type)}`}></i>
            <strong className="me-auto">{getTitle(toast.type)}</strong>
            <button
              type="button"
              className="btn-close btn-sm"
              onClick={() => removeToast(toast.id)}
              aria-label="Close"
            ></button>
          </div>
          <div className="toast-body" style={{ whiteSpace: 'pre-line' }}>{toast.message}</div>
        </div>
      ))}
    </div>
  );
};
