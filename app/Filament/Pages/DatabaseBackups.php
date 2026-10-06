<?php

namespace App\Filament\Pages;

use App\Services\BackupService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseBackups extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Database Backups';

    protected static ?string $title = 'Database Backups & Cloud Storage';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.database-backups';

    public function getBackups(): array
    {
        return app(BackupService::class)->getBackups();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label('Create Backup Now')
                ->icon('heroicon-o-circle-stack')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Create New Database Backup')
                ->modalDescription('This will generate a compressed .sql.gz backup of the current database.')
                ->action(function (BackupService $backupService) {
                    $result = $backupService->createBackup();

                    if ($result['success']) {
                        Notification::make()
                            ->title('Backup Created')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Backup Failed')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function downloadBackup(string $filename): BinaryFileResponse
    {
        $dir = app(BackupService::class)->getBackupDirectory();
        $path = $dir.'/'.basename($filename);

        abort_unless(file_exists($path), 404);

        return response()->download($path);
    }

    public function deleteBackup(string $filename): void
    {
        $deleted = app(BackupService::class)->deleteBackup($filename);

        if ($deleted) {
            Notification::make()
                ->title('Backup Deleted')
                ->body("File [{$filename}] was deleted.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Deletion Failed')
                ->danger()
                ->send();
        }
    }
}
