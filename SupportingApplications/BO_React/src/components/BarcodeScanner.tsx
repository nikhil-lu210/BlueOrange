import React, { useEffect, useRef, useState } from 'react';
import type { AttendanceType } from '../types';

interface BarcodeScannerProps {
  loading: boolean;
  onRecordEntry: (userid: string, type: AttendanceType) => void;
  statusMessage?: string;
}

export const BarcodeScanner: React.FC<BarcodeScannerProps> = ({
  loading,
  onRecordEntry,
  statusMessage = "Ready to scan attendance",
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

  const handleSubmit = async () => {
    if (userid.trim() && !loading) {
      const currentUserid = userid.trim();
      const currentType = attendanceType;

      // Clear input immediately
      setUserid('');
      barcodeRef.current = '';

      // Submit the attendance
      await onRecordEntry(currentUserid, currentType);

      // Keep focus on input
      setTimeout(() => inputRef.current?.focus(), 100);
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
      setUserid(barcodeRef.current); // Update input display
      e.preventDefault(); // Prevent manual input
    } else if (e.key === 'Enter') {
      // Enter key = end of barcode or manual input
      e.preventDefault();
      if (barcodeRef.current.trim()) {
        // Barcode scan completion
        setUserid(barcodeRef.current);
        setTimeout(() => handleSubmit(), 10);
      } else if (userid.trim()) {
        // Manual Enter key press
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
    <div className="card border-0 shadow-sm">
      {/* Status Bar as Card Header */}
      <div className="card-header bg-label-primary text-white border-0 py-2">
        <h6 className="mb-0">
          <i className="bi bi-info-circle me-2"></i>
          {statusMessage}
        </h6>
      </div>
      <div className="card-body p-4">
        <div className="row align-items-center g-4">
          {/* Attendance Type Selection - Card Style */}
          <div className="col-md-4">
            <div className="d-flex gap-2">
              <div className={`form-check card-radio ${attendanceType === 'Regular' ? 'selected' : ''}`}
                   onClick={() => handleTypeChange('Regular')}>
                <input
                  name="type"
                  className="form-check-input"
                  type="radio"
                  value="Regular"
                  id="typeRegular"
                  checked={attendanceType === 'Regular'}
                  onChange={() => handleTypeChange('Regular')}
                />
                <label className="form-check-label fw-semibold text-primary" htmlFor="typeRegular">
                  Regular
                </label>
              </div>
              <div className={`form-check card-radio ${attendanceType === 'Overtime' ? 'selected' : ''}`}
                   onClick={() => handleTypeChange('Overtime')}>
                <input
                  name="type"
                  className="form-check-input"
                  type="radio"
                  value="Overtime"
                  id="typeOvertime"
                  checked={attendanceType === 'Overtime'}
                  onChange={() => handleTypeChange('Overtime')}
                />
                <label className="form-check-label fw-semibold text-warning" htmlFor="typeOvertime">
                  Overtime
                </label>
              </div>
            </div>
          </div>

          {/* Employee ID Input */}
          <div className="col-md-8">
            <div className="input-group">
              <span className="input-group-text border-end-0">
                UID
              </span>
              <input
                type="text"
                id="userid"
                name="userid"
                ref={inputRef}
                value={userid}
                onChange={(e) => {
                  // Allow manual input but clear barcode buffer
                  setUserid(e.target.value);
                  barcodeRef.current = '';
                }}
                onKeyDown={handleKeyDown}
                onPaste={handlePaste}
                onContextMenu={handleContextMenu}
                className="form-control form-control-lg border-start-0"
                placeholder="20010101"
                disabled={loading}
                autoComplete="off"
                required
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
