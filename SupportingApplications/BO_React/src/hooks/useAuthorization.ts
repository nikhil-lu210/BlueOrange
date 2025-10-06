import { useState } from 'react';
import { API } from '../utils/api';

interface AuthUser {
  user_id: number;
  name: string;
  email: string;
  permissions: string[];
}

export const useAuthorization = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState('');
  const [modalMessage, setModalMessage] = useState('');
  const [authorizedUser, setAuthorizedUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(false);

  const authorizeUser = async (email: string, password: string): Promise<boolean> => {
    try {
      setLoading(true);

      const api = new API();
      const response = await fetch(`${api.baseURL}/offline-attendance/authorize`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      const result = await response.json();

      if (result.success) {
        setAuthorizedUser(result.data);
        setIsModalOpen(false);
        window.Toast?.show('Authorization successful', 'success');
        return true;
      } else {
        window.Toast?.show(result.message || 'Authorization failed', 'error');
        return false;
      }
    } catch (error) {
      console.error('Authorization error:', error);
      window.Toast?.show('Authorization failed. Please check your connection.', 'error');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const requestAuthorization = (title: string, message: string) => {
    setModalTitle(title);
    setModalMessage(message);
    setAuthorizedUser(null); // Clear previous authorization
    setIsModalOpen(true);
  };

  const clearAuthorization = () => {
    setAuthorizedUser(null);
  };

  const closeModal = () => {
    setIsModalOpen(false);
  };

  return {
    isModalOpen,
    modalTitle,
    modalMessage,
    authorizedUser,
    loading,
    authorizeUser,
    requestAuthorization,
    clearAuthorization,
    closeModal,
  };
};
