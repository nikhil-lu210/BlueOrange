<?php

namespace App\Http\Controllers\Application\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DatabaseBackupController extends Controller
{
    /**
     * Download database backup file
     */
    public function downloadBackup(Request $request, $filename)
    {
        // Validate filename format (si_app_db_backup_DDMMYYYY.sql)
        if (!preg_match('/^si_app_db_backup_\d{8}\.sql$/', $filename)) {
            abort(404, 'Invalid backup file format');
        }

        $filePath = storage_path('app/public/db_backup/' . $filename);

        // Check if file exists
        if (!File::exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        // Check if file is not empty
        if (File::size($filePath) === 0) {
            abort(404, 'Backup file is empty or corrupted');
        }

        // Return file download response
        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * List available backup files (for admin purposes)
     */
    public function listBackups()
    {
        $backupDir = storage_path('app/public/db_backup');

        if (!File::exists($backupDir)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup directory not found',
                'backups' => []
            ]);
        }

        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Only include valid backup files
            if (preg_match('/^si_app_db_backup_\d{8}\.sql$/', $filename)) {
                $backups[] = [
                    'filename' => $filename,
                    'size' => $this->formatBytes($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'download_url' => route('administration.database.backup.download', ['filename' => $filename])
                ];
            }
        }

        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Backups retrieved successfully',
            'backups' => $backups,
            'total' => count($backups)
        ]);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}
