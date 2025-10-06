import React, { useState } from 'react';

interface AuthorizationModalProps {
  isOpen: boolean;
  onClose: () => void;
  onAuthorize: (email: string, password: string) => Promise<boolean>;
  title: string;
  message: string;
  loading?: boolean;
}

export const AuthorizationModal: React.FC<AuthorizationModalProps> = ({
  isOpen,
  onClose,
  onAuthorize,
  title,
  message,
}) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setIsSubmitting(true);

    try {
      const success = await onAuthorize(email, password);
      if (success) {
        // Clear form on success
        setEmail('');
        setPassword('');
        setError('');
      }
    } catch (err) {
      setError('Authorization failed. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleClose = () => {
    setEmail('');
    setPassword('');
    setError('');
    onClose();
  };

  if (!isOpen) return null;

  return (
    <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">
              <i className="bi bi-shield-lock me-2 text-warning"></i>
              {title}
            </h5>
            <button
              type="button"
              className="btn-close"
              onClick={handleClose}
              disabled={isSubmitting}
            ></button>
          </div>

          <form onSubmit={handleSubmit} autoComplete='off'>
            <div className="modal-body">
              <div className="alert alert-warning d-flex align-items-center" role="alert">
                <i className="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                  <strong>Authorization Required</strong>
                  <div className="small">{message}</div>
                </div>
              </div>

              <div className="mb-3">
                <label htmlFor="authEmail" className="form-label">
                  Email Address <span className="text-danger">*</span>
                </label>
                <input
                  type="email"
                  className="form-control"
                  id="authEmail"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email address"
                  required
                  disabled={isSubmitting}
                  autoComplete="email"
                />
              </div>

              <div className="mb-3">
                <label htmlFor="authPassword" className="form-label">
                  Password <span className="text-danger">*</span>
                </label>
                <input
                  type="password"
                  className="form-control"
                  id="authPassword"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Enter your password"
                  required
                  disabled={isSubmitting}
                  autoComplete="current-password"
                />
              </div>

              {error && (
                <div className="alert alert-danger" role="alert">
                  <i className="bi bi-exclamation-circle-fill me-2"></i>
                  {error}
                </div>
              )}
            </div>

            <div className="modal-footer">
              <button
                type="button"
                className="btn btn-secondary"
                onClick={handleClose}
                disabled={isSubmitting}
              >
                Cancel
              </button>
              <button
                type="submit"
                className="btn btn-warning"
                disabled={isSubmitting || !email.trim() || !password.trim()}
              >
                {isSubmitting ? (
                  <>
                    <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Authorizing...
                  </>
                ) : (
                  <>
                    <i className="bi bi-shield-check me-2"></i>
                    Authorize
                  </>
                )}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};
