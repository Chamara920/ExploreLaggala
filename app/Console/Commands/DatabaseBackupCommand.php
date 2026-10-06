<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('db:backup {--disk= : Remote storage disk to copy the backup (e.g. s3)} {--keep=14 : Number of days to retain backups}')]
#[Description('Create a compressed database backup with retention cleanup and optional remote storage upload')]
class DatabaseBackupCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $disk = $this->option('disk');
        $keepDays = (int) ($this->option('keep') ?: 14);

        $this->info('Starting database backup...');

        $result = $backupService->createBackup($disk, $keepDays);

        if (! $result['success']) {
            $this->error('Backup failed: '.($result['message'] ?? 'Unknown error'));

            return Command::FAILURE;
        }

        $this->info('Database backup completed successfully!');
        $this->table(
            ['Property', 'Value'],
            [
                ['File', $result['filename']],
                ['Size', $result['size_formatted']],
                ['Database Driver', $result['driver']],
                ['Duration', $result['duration_seconds'].'s'],
                ['Remote Upload', ($result['remote_uploaded'] ?? false) ? "Uploaded to [{$disk}]" : 'None (Local only)'],
                ['Location', $result['path']],
            ]
        );

        return Command::SUCCESS;
    }
}
