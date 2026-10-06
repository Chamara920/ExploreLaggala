<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

#[Signature('media:sync-to-remote {--disk=s3 : Remote target disk} {--dry-run : List files without uploading} {--delete-local : Remove local files after successful upload to free disk space} {--force : Bypass confirmation}')]
#[Description('Sync existing local media files from storage/app/public to remote cloud storage')]
class MediaSyncToRemoteCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $remoteDisk = (string) ($this->option('disk') ?: 's3');
        $isDryRun = (bool) $this->option('dry-run');
        $deleteLocal = (bool) $this->option('delete-local');

        if (! config("filesystems.disks.{$remoteDisk}")) {
            $this->error("Filesystem disk [{$remoteDisk}] is not configured in config/filesystems.php.");

            return Command::FAILURE;
        }

        $localDir = storage_path('app/public');
        if (! File::isDirectory($localDir)) {
            $this->error("Local storage directory [{$localDir}] does not exist.");

            return Command::FAILURE;
        }

        $allFiles = File::allFiles($localDir);
        $totalFiles = count($allFiles);

        if ($totalFiles === 0) {
            $this->info('No files found to sync in storage/app/public.');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalFiles} files in storage/app/public.");
        $this->info("Target remote disk: [{$remoteDisk}]");

        if ($isDryRun) {
            $this->warn('DRY RUN MODE: No files will be uploaded or deleted.');
        } elseif ($deleteLocal && ! $this->option('force')) {
            $confirm = $this->confirm(
                'WARNING: Local files will be deleted after successful upload to free server disk. Continue?',
                false
            );

            if (! $confirm) {
                $this->warn('Sync cancelled.');

                return Command::SUCCESS;
            }
        }

        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        $uploaded = 0;
        $failed = 0;

        foreach ($allFiles as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            if ($isDryRun) {
                $this->line(" [Dry Run] {$relativePath} (".number_format($file->getSize() / 1024, 1).' KB)');
                $bar->advance();

                continue;
            }

            try {
                $stream = fopen($file->getRealPath(), 'r');
                if ($stream) {
                    $success = Storage::disk($remoteDisk)->put($relativePath, $stream);
                    fclose($stream);

                    if ($success) {
                        $uploaded++;

                        if ($deleteLocal) {
                            File::delete($file->getRealPath());
                        }
                    } else {
                        $failed++;
                    }
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("\nFailed to upload [{$relativePath}]: ".$e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($isDryRun) {
            $this->info("Dry run finished. {$totalFiles} files inspected.");
        } else {
            $this->info("Sync completed! Successfully uploaded: {$uploaded} file(s).");
            if ($failed > 0) {
                $this->warn("Failed uploads: {$failed} file(s).");
            }
        }

        return Command::SUCCESS;
    }
}
