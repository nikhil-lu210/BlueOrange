import React, { useEffect, useRef, useState } from 'react';
import type { User, AttendanceType } from '../types';

interface ScannerPanelProps {
  currentUser: (User & { suggestedType?: AttendanceType }) | null;
  loading: boolean;
  onUserScanned: (userid: string) => void;
  onRecordEntry: (type: AttendanceType) => void;
  onClearUser: () => void;
}

export const ScannerPanel: React.FC<ScannerPanelProps> = ({
  currentUser,
  loading,
  onUserScanned,
  onRecordEntry,
  onClearUser,
}) => {
  const [barcodeValue, setBarcodeValue] = useState('');
  const inputRef = useRef<HTMLInputElement | null>(null);
  const debounceRef = useRef<number | null>(null);

  useEffect(() => {
    inputRef.current?.focus();
    return () => {
      if (debounceRef.current) {
        window.clearTimeout(debounceRef.current);
      }
    };
  }, []);

  const handleInput = (value: string) => {
    setBarcodeValue(value);
    if (debounceRef.current) {
      clearTimeout(debounceRef.current);
    }
    debounceRef.current = window.setTimeout(() => {
      if (value.trim()) handleScan();
    }, 500);
  };

  const handleScan = () => {
    const userid = barcodeValue.trim();
    if (userid && !loading) {
      onUserScanned(userid);
      setBarcodeValue('');
    }
  };

  const clearUser = () => {
    onClearUser();
    setBarcodeValue('');
    setTimeout(() => inputRef.current?.focus(), 0);
  };

  return (
    <div className="scanner-panel p-4">
      <h5 className="mb-3">
        <i className="bi bi-qr-code-scan me-2"></i>
        Barcode Scanner
      </h5>

      <div className="mb-3">
        <label htmlFor="barcodeInput" className="form-label">Scan or Enter User ID</label>
        <input
          id="barcodeInput"
          ref={inputRef}
          value={barcodeValue}
          onChange={(e) => handleInput(e.target.value)}
          type="text"
          className="form-control barcode-input"
          placeholder="Scan barcode or enter user ID"
          disabled={loading}
          onKeyUp={(e) => {
            if (e.key === 'Enter') handleScan();
          }}
        />
      </div>

      {currentUser ? (
        <div className="user-info">
          <div className="d-flex align-items-center justify-content-between">
            <div>
              <h6 className="mb-1">{currentUser.alias_name}</h6>
              <small className="opacity-75">{currentUser.name} ({currentUser.userid})</small>
            </div>
            <button className="btn btn-outline-light btn-sm" onClick={clearUser} disabled={loading}>
              <i className="bi bi-x"></i>
            </button>
          </div>
        </div>
      ) : (
        <div className="text-muted small mt-3">
          <i className="bi bi-info-circle me-1"></i>
          Scan a barcode or enter a user ID to begin
        </div>
      )}

      {currentUser && (
        <div className="mt-3">
          <div className="row g-2">
            <div className="col-12">
              <button
                className="btn btn-primary btn-action w-100"
                onClick={() => onRecordEntry((currentUser.suggestedType || 'Regular'))}
                disabled={loading}
              >
                <i className="bi bi-plus-circle me-1"></i>
                Record {currentUser.suggestedType || 'Regular'} Entry
              </button>
            </div>
            {currentUser.suggestedType === 'Regular' && (
              <div className="col-12">
                <button className="btn btn-warning btn-action w-100" onClick={() => onRecordEntry('Overtime')} disabled={loading}>
                  <i className="bi bi-plus-circle me-1"></i>
                  Record Overtime Entry
                </button>
              </div>
            )}
            {currentUser.suggestedType === 'Overtime' && (
              <div className="col-12">
                <button className="btn btn-success btn-action w-100" onClick={() => onRecordEntry('Regular')} disabled={loading}>
                  <i className="bi bi-plus-circle me-1"></i>
                  Record Regular Entry
                </button>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};