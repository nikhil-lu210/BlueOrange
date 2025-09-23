<?php

namespace App\Mail\Administration\DatabaseBackup;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DatabaseBackupReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filename;
    public $downloadUrl;
    public $fileSize;
    public $backupDate;

    /**
     * Create a new message instance.
     */
    public function __construct($filename, $downloadUrl, $fileSize, $backupDate)
    {
        $this->filename = $filename;
        $this->downloadUrl = $downloadUrl;
        $this->fileSize = $fileSize;
        $this->backupDate = $backupDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Extract date from filename (si_app_db_backup_23092025.sql)
        $dateStr = '';
        if (preg_match('/si_app_db_backup_(\d{8})\.sql/', $this->filename, $matches)) {
            $date = Carbon::createFromFormat('dmY', $matches[1]);
            $dateStr = $date->format('jS F, Y');
        }

        $appName = config('app.name', 'Laravel');
        $subject = $appName . ' Database Backup of ' . $dateStr;

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.administration.backup.database_backup_ready',
            with: [
                'filename' => $this->filename,
                'downloadUrl' => $this->downloadUrl,
                'fileSize' => $this->fileSize,
                'backupDate' => $this->backupDate,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
