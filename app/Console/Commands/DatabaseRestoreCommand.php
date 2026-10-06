<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('db:restore {--file= : Specific backup filename to restore} {--force : Force the operation without confirmation prompt}')]
#[Description('Restore the database from a backup file')]
class DatabaseRestoreCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $file = $this->option('file');

        if (! $file) {
            $backups = $backupService->getBackups();

            if (empty($backups)) {
                $this->error('No backup files found in storage/app/backups.');

                return Command::FAILURE;
            }

            $choices = [];
            foreach ($backups as $index => $b) {
                $choices[$b['filename']] = "{$b['filename']} ({$b['size_formatted']}, {$b['age_human']})";
            }

            $file = $this->choice(
                'Select a backup file to restore:',
                array_keys($choices),
                0
            );
        }

        if (! $this->option('force')) {
            $confirmed = $this->confirm(
                "WARNING: Restoring [{$file}] will OVERWRITE current database records. Do you wish to continue?",
                false
            );

            if (! $confirmed) {
                $this->warn('Database restore cancelled.');

                return Command::SUCCESS;
            }
        }

        $this->info("Restoring database from [{$file}]...");

        $result = $backupService->restoreBackup($file);

        if (! $result['success']) {
            $this->error('Restore failed: '.$result['message']);

            return Command::FAILURE;
        }

        $this->info($result['message']);

        return Command::SUCCESS;
    }
}
