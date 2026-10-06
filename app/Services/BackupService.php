<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupService
{
    /**
     * Get the directory where local database backups are stored.
     */
    public function getBackupDirectory(): string
    {
        $path = storage_path('app/backups');

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
            File::put($path.'/.gitignore', "*\n!.gitignore\n");
        }

        return $path;
    }

    /**
     * Create a compressed database backup.
     *
     * @return array{success: bool, filename?: string, path?: string, size_bytes?: int, size_formatted?: string, driver?: string, duration_seconds?: float, remote_uploaded?: bool, message?: string}
     */
    public function createBackup(?string $remoteDisk = null, int $keepDays = 14): array
    {
        $startTime = microtime(true);
        $directory = $this->getBackupDirectory();

        $connection = config('database.default', 'mysql');
        $dbConfig = config("database.connections.{$connection}", []);
        $driver = $dbConfig['driver'] ?? 'mysql';

        $timestamp = Carbon::now()->format('Y-m-d_His');
        $baseFilename = "backup_{$timestamp}_{$driver}";
        $rawTempSqlPath = $directory."/{$baseFilename}.sql";
        $gzFinalPath = $directory."/{$baseFilename}.sql.gz";

        try {
            if ($driver === 'sqlite') {
                $this->backupSqlite($dbConfig, $gzFinalPath);
            } elseif ($driver === 'mysql' || $driver === 'mariadb') {
                $this->backupMysql($dbConfig, $rawTempSqlPath, $gzFinalPath);
            } elseif ($driver === 'pgsql') {
                $this->backupPostgres($dbConfig, $rawTempSqlPath, $gzFinalPath);
            } else {
                throw new Exception("Database driver [{$driver}] is not supported for automated backups.");
            }

            if (! File::exists($gzFinalPath)) {
                throw new Exception('Backup archive was not created.');
            }

            $sizeBytes = File::size($gzFinalPath);
            $filename = basename($gzFinalPath);
            $remoteUploaded = false;

            // Optional remote cloud storage upload
            if ($remoteDisk && config("filesystems.disks.{$remoteDisk}")) {
                try {
                    $stream = fopen($gzFinalPath, 'r');
                    if ($stream) {
                        Storage::disk($remoteDisk)->put("backups/db/{$filename}", $stream);
                        fclose($stream);
                        $remoteUploaded = true;
                    }
                } catch (Exception $e) {
                    Log::warning("Remote backup upload to [{$remoteDisk}] failed: ".$e->getMessage());
                }
            }

            // Cleanup old backups
            $this->cleanOldBackups($keepDays);

            $duration = round(microtime(true) - $startTime, 2);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $gzFinalPath,
                'size_bytes' => $sizeBytes,
                'size_formatted' => $this->formatBytes($sizeBytes),
                'driver' => $driver,
                'duration_seconds' => $duration,
                'remote_uploaded' => $remoteUploaded,
                'message' => "Backup [{$filename}] created successfully in {$duration}s.",
            ];
        } catch (Exception $e) {
            Log::error('Database backup error: '.$e->getMessage(), ['exception' => $e]);

            if (File::exists($rawTempSqlPath)) {
                File::delete($rawTempSqlPath);
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Backup a SQLite database.
     */
    protected function backupSqlite(array $config, string $gzFinalPath): void
    {
        $dbPath = $config['database'] ?? database_path('database.sqlite');

        if (! File::exists($dbPath)) {
            throw new Exception("SQLite database file not found at: {$dbPath}");
        }

        $tempSqlite = $gzFinalPath.'.tmp';

        try {
            // VACUUM INTO creates a clean, transactionally consistent snapshot
            DB::statement("VACUUM INTO '{$tempSqlite}'");
        } catch (Exception) {
            // Fallback: standard file copy
            File::copy($dbPath, $tempSqlite);
        }

        $this->compressFile($tempSqlite, $gzFinalPath);
        File::delete($tempSqlite);
    }

    /**
     * Backup a MySQL database using mysqldump or PDO streaming fallback.
     */
    protected function backupMysql(array $config, string $rawSqlPath, string $gzFinalPath): void
    {
        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';
        $port = (string) ($config['port'] ?? 3306);

        $mysqldumpFound = false;

        // Check if mysqldump binary is available in PATH
        try {
            $checkProcess = new Process(['mysqldump', '--version']);
            $checkProcess->run();
            $mysqldumpFound = $checkProcess->isSuccessful();
        } catch (Exception) {
            $mysqldumpFound = false;
        }

        if ($mysqldumpFound) {
            $command = [
                'mysqldump',
                "--host={$host}",
                "--port={$port}",
                "--user={$username}",
                '--single-transaction',
                '--quick',
                '--skip-lock-tables',
                '--default-character-set=utf8mb4',
            ];

            if (! empty($password)) {
                $command[] = "--password={$password}";
            }

            $command[] = $database;

            $process = new Process($command);
            $process->setTimeout(300);

            $fileHandle = fopen($rawSqlPath, 'w');
            if (! $fileHandle) {
                throw new Exception("Could not open destination file: {$rawSqlPath}");
            }

            $process->run(function ($type, $buffer) use ($fileHandle) {
                if ($type === Process::OUT) {
                    fwrite($fileHandle, $buffer);
                }
            });

            fclose($fileHandle);

            if (! $process->isSuccessful() || File::size($rawSqlPath) === 0) {
                // If mysqldump encountered permission issues, fallback to PDO dump
                $mysqldumpFound = false;
                File::delete($rawSqlPath);
            }
        }

        // Pure PHP PDO streaming fallback (works in any container/environment)
        if (! $mysqldumpFound) {
            $this->pdoMysqlDump($rawSqlPath);
        }

        // Compress SQL to GZ
        $this->compressFile($rawSqlPath, $gzFinalPath);
        File::delete($rawSqlPath);
    }

    /**
     * Pure PHP PDO MySQL Dump Fallback.
     */
    protected function pdoMysqlDump(string $rawSqlPath): void
    {
        $handle = fopen($rawSqlPath, 'w');
        if (! $handle) {
            throw new Exception("Unable to write SQL dump file: {$rawSqlPath}");
        }

        fwrite($handle, "-- Explore Laggala MySQL Dump Fallback\n");
        fwrite($handle, '-- Generated: '.Carbon::now()->toIso8601String()."\n\n");
        fwrite($handle, "SET foreign_key_checks = 0;\n\n");

        $tables = DB::select('SHOW FULL TABLES WHERE Table_Type = "BASE TABLE"');

        foreach ($tables as $tableRow) {
            $tableArray = (array) $tableRow;
            $tableName = reset($tableArray);

            // Structure
            $createTable = DB::selectOne("SHOW CREATE TABLE `{$tableName}`");
            $createTableArray = (array) $createTable;
            $createStatement = $createTableArray['Create Table'] ?? '';

            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            fwrite($handle, $createStatement.";\n\n");

            // Data in chunks
            $rows = DB::table($tableName)->cursor();
            $batch = [];
            $columns = null;

            foreach ($rows as $row) {
                $rowArray = (array) $row;
                if ($columns === null) {
                    $columns = array_map(fn ($col) => "`{$col}`", array_keys($rowArray));
                }

                $values = array_map(function ($val) {
                    if (is_null($val)) {
                        return 'NULL';
                    }

                    return DB::getPdo()->quote((string) $val);
                }, array_values($rowArray));

                $batch[] = '('.implode(', ', $values).')';

                if (count($batch) >= 200) {
                    $sql = "INSERT INTO `{$tableName}` (".implode(', ', $columns).") VALUES\n".implode(",\n", $batch).";\n";
                    fwrite($handle, $sql);
                    $batch = [];
                }
            }

            if (! empty($batch) && $columns !== null) {
                $sql = "INSERT INTO `{$tableName}` (".implode(', ', $columns).") VALUES\n".implode(",\n", $batch).";\n\n";
                fwrite($handle, $sql);
            }
        }

        fwrite($handle, "SET foreign_key_checks = 1;\n");
        fclose($handle);
    }

    /**
     * Backup a PostgreSQL database.
     */
    protected function backupPostgres(array $config, string $rawSqlPath, string $gzFinalPath): void
    {
        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';
        $port = (string) ($config['port'] ?? 5432);

        $process = new Process([
            'pg_dump',
            "-h{$host}",
            "-p{$port}",
            "-U{$username}",
            "-f{$rawSqlPath}",
            $database,
        ], null, ['PGPASSWORD' => $password]);

        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new Exception('PostgreSQL pg_dump failed: '.$process->getErrorOutput());
        }

        $this->compressFile($rawSqlPath, $gzFinalPath);
        File::delete($rawSqlPath);
    }

    /**
     * Compress a file using Gzip.
     */
    protected function compressFile(string $sourcePath, string $destPath): void
    {
        $srcHandle = fopen($sourcePath, 'rb');
        $destHandle = gzopen($destPath, 'wb9');

        if (! $srcHandle || ! $destHandle) {
            throw new Exception('Failed to open streams for gzip compression.');
        }

        while (! feof($srcHandle)) {
            gzwrite($destHandle, fread($srcHandle, 1024 * 512));
        }

        fclose($srcHandle);
        gzclose($destHandle);
    }

    /**
     * Decompress a Gzip file.
     */
    protected function decompressFile(string $gzPath, string $destPath): void
    {
        $srcHandle = gzopen($gzPath, 'rb');
        $destHandle = fopen($destPath, 'wb');

        if (! $srcHandle || ! $destHandle) {
            throw new Exception('Failed to open streams for gzip decompression.');
        }

        while (! gzeof($srcHandle)) {
            fwrite($destHandle, gzread($srcHandle, 1024 * 512));
        }

        gzclose($srcHandle);
        fclose($destHandle);
    }

    /**
     * Restore database from a backup file.
     *
     * @return array{success: bool, message: string}
     */
    public function restoreBackup(string $filename): array
    {
        $directory = $this->getBackupDirectory();
        $filePath = $directory.'/'.basename($filename);

        if (! File::exists($filePath)) {
            return [
                'success' => false,
                'message' => "Backup file [{$filename}] does not exist in {$directory}.",
            ];
        }

        $connection = config('database.default', 'mysql');
        $dbConfig = config("database.connections.{$connection}", []);
        $driver = $dbConfig['driver'] ?? 'mysql';

        try {
            $isGz = str_ends_with($filePath, '.gz');
            $decompressedSql = $filePath;

            if ($isGz) {
                $decompressedSql = $directory.'/temp_restore_'.time().'.sql';
                $this->decompressFile($filePath, $decompressedSql);
            }

            if ($driver === 'sqlite') {
                $dbPath = $dbConfig['database'] ?? database_path('database.sqlite');
                File::copy($decompressedSql, $dbPath);
            } else {
                // Execute SQL script
                $sqlContent = File::get($decompressedSql);
                DB::unprepared($sqlContent);
            }

            if ($isGz && File::exists($decompressedSql)) {
                File::delete($decompressedSql);
            }

            return [
                'success' => true,
                'message' => "Database restored successfully from [{$filename}].",
            ];
        } catch (Exception $e) {
            Log::error('Database restore error: '.$e->getMessage(), ['exception' => $e]);

            return [
                'success' => false,
                'message' => 'Restore failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * List all local backups with metadata.
     *
     * @return array<int, array{filename: string, size_bytes: int, size_formatted: string, created_at: string, age_human: string}>
     */
    public function getBackups(): array
    {
        $directory = $this->getBackupDirectory();
        $files = File::glob($directory.'/backup_*');

        $backups = [];

        foreach ($files as $file) {
            if (str_ends_with($file, '.gitignore') || str_ends_with($file, '.tmp')) {
                continue;
            }

            $size = File::size($file);
            $modified = File::lastModified($file);
            $dt = Carbon::createFromTimestamp($modified);

            $backups[] = [
                'filename' => basename($file),
                'size_bytes' => $size,
                'size_formatted' => $this->formatBytes($size),
                'created_at' => $dt->format('Y-m-d H:i:s'),
                'age_human' => $dt->diffForHumans(),
            ];
        }

        // Sort descending by created_at
        usort($backups, fn ($a, $b) => strcmp($b['filename'], $a['filename']));

        return $backups;
    }

    /**
     * Delete a specific backup file.
     */
    public function deleteBackup(string $filename): bool
    {
        $directory = $this->getBackupDirectory();
        $path = $directory.'/'.basename($filename);

        if (File::exists($path)) {
            return File::delete($path);
        }

        return false;
    }

    /**
     * Cleanup backups older than retention days.
     */
    public function cleanOldBackups(int $keepDays = 14): int
    {
        $directory = $this->getBackupDirectory();
        $files = File::glob($directory.'/backup_*');
        $deleted = 0;
        $cutoff = Carbon::now()->subDays($keepDays)->timestamp;

        foreach ($files as $file) {
            if (File::lastModified($file) < $cutoff) {
                File::delete($file);
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Human readable byte formatting.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
