import type { ToastType } from '../../types';

interface ToastItem {
  id: number;
  type: ToastType;
  message: string;
}

export type { ToastItem };

declare global {
  interface Window {
    Toast?: {
      show: (message: string, type?: ToastType) => void;
    };
  }
}