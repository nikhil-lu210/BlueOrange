import React, { useEffect, useRef, useState } from 'react';
import type { AttendanceType } from '../types';

interface BarcodeScannerProps {
  loading: boolean;
  onRecordEntry: (userid: string, type: AttendanceType) => void;
}

export const BarcodeScanner: React.FC<BarcodeScannerProps> = ({
  loading,
  onRecordEntry,
}) => {
  const [attendanceType, setAttendanceType] = useState<AttendanceType>('Regular');
  const [userid, setUserid] = useState('');
  const inputRef = useRef<HTMLInputElement | null>(null);
  const barcodeRef = useRef<string>('');
  const lastKeyTimeRef = useRef<number>(Date.now());

  useEffect(() => {
    inputRef.current?.focus();
  }, []);

  const handleTypeChange = (type: AttendanceType) => {
    setAttendanceType(type);
    setUserid('');
    setTimeout(() => inputRef.current?.focus(), 0);
  };

  const handleSubmit = () => {
    if (userid.trim() && !loading) {
      onRecordEntry(userid.trim(), attendanceType);
      setUserid('');
      setTimeout(() => inputRef.current?.focus(), 0);
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    const now = Date.now();
    const timeDiff = now - lastKeyTimeRef.current;
    const MAX_INTERVAL = 50; // Max time between keystrokes to qualify as barcode

    if (timeDiff > MAX_INTERVAL) {
      barcodeRef.current = '';
    }

    lastKeyTimeRef.current = now;
    const key = e.key;

    if (/^[\w\d]$/.test(key)) {
      // Alphanumeric character - add to barcode
      barcodeRef.current += key;
      e.preventDefault(); // Prevent manual input
    } else if (e.key === 'Enter') {
      // Enter key = end of barcode
      e.preventDefault();
      if (barcodeRef.current.trim()) {
        setUserid(barcodeRef.current);
        handleSubmit();
      }
      barcodeRef.current = '';
    } else {
      // Other keys - prevent all manual input
      e.preventDefault();
    }
  };

  const handlePaste = (e: React.ClipboardEvent) => {
    e.preventDefault(); // Block pasting
  };

  const handleContextMenu = (e: React.MouseEvent) => {
    e.preventDefault(); // Block right-click
  };

  return (
    <div className="scanner-panel p-4">
      <h5 className="mb-3">
        <i className="bi bi-qr-code-scan me-2"></i>
        Barcode Attendance
      </h5>

      {/* Attendance Type Selection */}
      <div className="mb-3">
        <div className="row">
          <div className="col-md mb-md-0 mb-2">
            <div className="form-check custom-option custom-option-basic form-check-primary">
              <label className="form-check-label custom-option-content" htmlFor="typeRegular">
                <input
                  name="type"
                  className="form-check-input"
                  type="radio"
                  value="Regular"
                  id="typeRegular"
                  checked={attendanceType === 'Regular'}
                  onChange={() => handleTypeChange('Regular')}
                />
                <span className="custom-option-header pb-0">
                  <span className="h6 mb-0 text-primary text-bold">Regular</span>
                </span>
              </label>
            </div>
          </div>
          <div className="col-md">
            <div className="form-check custom-option custom-option-basic form-check-warning">
              <label className="form-check-label custom-option-content" htmlFor="typeOvertime">
                <input
                  name="type"
                  className="form-check-input"
                  type="radio"
                  value="Overtime"
                  id="typeOvertime"
                  checked={attendanceType === 'Overtime'}
                  onChange={() => handleTypeChange('Overtime')}
                />
                <span className="custom-option-header pb-0">
                  <span className="h6 mb-0 text-warning text-bold">Overtime</span>
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>

      {/* Employee ID Input */}
      <div className="mb-3">
        <label className="form-label text-bold text-dark">
          Employee ID <strong className="text-danger">*</strong>
        </label>
        <div className="input-group input-group-merge">
          <span className="input-group-text" style={{ paddingRight: '2px' }}>UID</span>
          <input
            type="text"
            id="userid"
            name="userid"
            ref={inputRef}
            value={userid}
            onChange={(e) => setUserid(e.target.value)}
            onKeyDown={handleKeyDown}
            // onPaste={handlePaste}
            // onContextMenu={handleContextMenu}
            className="form-control"
            placeholder="20010101"
            disabled={loading}
            autoComplete="off"
            required
          />
        </div>
      </div>

      {/* Submit Button */}
      <div className="mb-3">
        <button
          type="button"
          className="btn btn-primary w-100"
          onClick={handleSubmit}
          disabled={loading || !userid.trim()}
        >
          <i className="bi bi-plus-circle me-1"></i>
          Record {attendanceType} Entry
        </button>
      </div>

      {/* Instructions */}
      <div className="text-muted small">
        <i className="bi bi-info-circle me-1"></i>
        Select attendance type, then scan employee barcode or enter ID manually
      </div>
    </div>
  );
};
