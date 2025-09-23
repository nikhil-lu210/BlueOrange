<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\DatabaseBackup\DatabaseBackupReadyMail;
use App\Mail\Administration\DatabaseBackup\DatabaseBackupFailedMail;
use Carbon\Carbon;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database-daily {--disable-notifications : Disable email notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily database backup with 3-day retention and email notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting daily database backup...');

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/public/db_backup');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate filename with current date
            $date = Carbon::now()->format('dmY');
            $filename = "si_app_db_backup_{$date}.sql";
            $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');

            // Try mysqldump first
            $mysqldumpPath = $this->getMysqldumpPath();

            $this->info('Looking for mysqldump...');
            $this->info('Found mysqldump at: ' . ($mysqldumpPath ?: 'NOT FOUND'));

            if ($mysqldumpPath) {
                $command = sprintf(
                    '%s --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s',
                    escapeshellarg($mysqldumpPath),
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    escapeshellarg($filepath)
                );

                // Execute backup
                $this->info('Creating database dump using mysqldump...');
                exec($command, $output, $returnCode);

                if ($returnCode !== 0) {
                    throw new \Exception('Database backup failed with return code: ' . $returnCode);
                }
            } else {
                // Fallback: Use Laravel's database connection for shared hosting
                $this->info('mysqldump not found, using Laravel database connection...');
                $this->createBackupUsingLaravel($filepath);
            }

            // Verify backup file was created and has content
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                throw new \Exception('Backup file was not created or is empty');
            }

            $this->info("Database backup created successfully: {$filename}");
            $this->info("File size: " . $this->formatBytes(filesize($filepath)));

            // Clean old backups (keep only last 3 days)
            $this->cleanOldBackups($backupDir);

            // Send email notification with download link (unless disabled)
            if (!$this->option('disable-notifications')) {
                $this->sendNotification($filename, $filepath);
            }

            $this->info('Daily database backup completed successfully!');

        } catch (\Exception $e) {
            $this->error('Database backup failed: ' . $e->getMessage());
            if (!$this->option('disable-notifications')) {
                $this->sendFailureNotification($e->getMessage());
            }
            return 1;
        }

        return 0;
    }

    /**
     * Clean old backup files (keep only last 3 days)
     */
    private function cleanOldBackups($backupDir)
    {
        $this->info('Cleaning old backup files...');

        $files = glob($backupDir . DIRECTORY_SEPARATOR . 'si_app_db_backup_*.sql');
        $cutoffDate = Carbon::now()->subDays(3);

        $deletedCount = 0;
        foreach ($files as $file) {
            $filename = basename($file);

            // Extract date from filename (si_app_db_backup_DDMMYYYY.sql)
            if (preg_match('/si_app_db_backup_(\d{8})\.sql/', $filename, $matches)) {
                $fileDate = Carbon::createFromFormat('dmY', $matches[1]);

                if ($fileDate->lt($cutoffDate)) {
                    if (unlink($file)) {
                        $deletedCount++;
                        $this->info("Deleted old backup: {$filename}");
                    }
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("Cleaned {$deletedCount} old backup file(s)");
        } else {
            $this->info('No old backup files to clean');
        }
    }

    /**
     * Send email notification with download link
     */
    private function sendNotification($filename, $filepath)
    {
        try {
            $downloadUrl = route('administration.database.backup.download', ['filename' => $filename]);
            $fileSize = $this->formatBytes(filesize($filepath));
            $backupDate = Carbon::now()->format('d M Y, H:i:s');

            $emails = [
                'nigel.pi@staff-india.com',
                'nigel.staffindia@gmail.com'
            ];

            foreach ($emails as $email) {
                Mail::to($email)->send(new DatabaseBackupReadyMail(
                    $filename,
                    $downloadUrl,
                    $fileSize,
                    $backupDate
                ));
            }

            $this->info('Email notifications sent successfully');

        } catch (\Exception $e) {
            $this->warn('Failed to send email notifications: ' . $e->getMessage());
        }
    }

    /**
     * Send failure notification
     */
    private function sendFailureNotification($errorMessage)
    {
        try {
            $emails = [
                'nigel.pi@staff-india.com',
                'nigel.staffindia@gmail.com',
                'nikhil.lu10@gmail.com',
                'steve.it@staff-india.com',
            ];

            foreach ($emails as $email) {
                Mail::to($email)->send(new DatabaseBackupFailedMail(
                    $errorMessage,
                    Carbon::now()->format('d M Y, H:i:s')
                ));
            }

        } catch (\Exception $e) {
            $this->error('Failed to send failure notification: ' . $e->getMessage());
        }
    }

    /**
     * Get mysqldump executable path
     */
    private function getMysqldumpPath()
    {
        // Check if path is specified in .env file
        $customPath = env('MYSQLDUMP_PATH');
        if ($customPath && file_exists($customPath)) {
            return $customPath;
        }

        // Common Windows paths for Laragon/XAMPP (development)
        $windowsPaths = [
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.30\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
        ];

        // Common Linux/Shared hosting paths
        $linuxPaths = [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/cpanel/ea-php82/root/usr/bin/mysqldump',
            '/opt/cpanel/ea-php81/root/usr/bin/mysqldump',
            '/opt/cpanel/ea-php80/root/usr/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            '/opt/mysql/bin/mysqldump',
            '/usr/mysql/bin/mysqldump',
            '/home/*/mysql/bin/mysqldump',
            '/home/*/bin/mysqldump',
            '/usr/bin/mariadb-dump',
            '/usr/local/bin/mariadb-dump',
        ];

        // Check Windows paths first
        foreach ($windowsPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Check Linux paths
        foreach ($linuxPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try to find in PATH (works for both Windows and Linux)
        $output = [];
        $returnCode = 0;

        // Try different commands based on OS
        if (PHP_OS_FAMILY === 'Windows') {
            exec('where mysqldump 2>nul', $output, $returnCode);
        } else {
            exec('which mysqldump 2>/dev/null', $output, $returnCode);
        }

        if ($returnCode === 0 && !empty($output)) {
            $path = trim($output[0]);
            // Verify the path exists and is executable
            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        // Additional check: Try direct execution
        if (PHP_OS_FAMILY !== 'Windows') {
            exec('mysqldump --version 2>/dev/null', $versionOutput, $versionReturnCode);
            if ($versionReturnCode === 0) {
                // mysqldump is in PATH, try to get full path
                exec('which mysqldump 2>/dev/null', $whichOutput, $whichReturnCode);
                if ($whichReturnCode === 0 && !empty($whichOutput)) {
                    $fullPath = trim($whichOutput[0]);
                    if (file_exists($fullPath)) {
                        return $fullPath;
                    }
                }
            }
        }

        // Try alternative commands for MariaDB
        $output = [];
        $returnCode = 0;

        if (PHP_OS_FAMILY === 'Windows') {
            exec('where mariadb-dump 2>nul', $output, $returnCode);
        } else {
            exec('which mariadb-dump 2>/dev/null', $output, $returnCode);
        }

        if ($returnCode === 0 && !empty($output)) {
            return trim($output[0]);
        }

        return null;
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

    /**
     * Create backup using Laravel's database connection (fallback for shared hosting)
     */
    private function createBackupUsingLaravel($filepath)
    {
        $this->info('Creating backup using Laravel database connection...');

        try {
            $database = config('database.connections.mysql.database');
            $tables = \DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $database;

            $sql = "-- Database Backup Created: " . now() . "\n";
            $sql .= "-- Database: {$database}\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Get table structure
                $createTable = \DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable->{'Create Table'} . ";\n\n";

                // Get table data
                $rows = \DB::table($tableName)->get();

                if ($rows->count() > 0) {
                    $sql .= "LOCK TABLES `{$tableName}` WRITE;\n";

                    foreach ($rows as $row) {
                        $values = array_map(function($value) {
                            if ($value === null) {
                                return 'NULL';
                            } elseif (is_string($value)) {
                                return "'" . addslashes($value) . "'";
                            } else {
                                return $value;
                            }
                        }, (array) $row);

                        $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(',', $values) . ");\n";
                    }

                    $sql .= "UNLOCK TABLES;\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            file_put_contents($filepath, $sql);
            $this->info('Backup created successfully using Laravel database connection');

        } catch (\Exception $e) {
            throw new \Exception('Failed to create backup using Laravel: ' . $e->getMessage());
        }
    }
}
