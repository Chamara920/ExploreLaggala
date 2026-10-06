<x-filament-panels::page>
    @php
        $backups = $this->getBackups();
        $defaultConn = config('database.default', 'mysql');
        $dbDriver = config("database.connections.{$defaultConn}.driver", 'mysql');
        $mediaDisk = config('filesystems.media_disk', 'public');
        $mediaUrl = config('filesystems.media_url');
    @endphp

    <div class="space-y-6">

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Database Engine</div>
                <div class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400 uppercase">{{ $dbDriver }}</div>
                <div class="mt-1 text-xs text-gray-500">Connection: {{ $defaultConn }}</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Available Backups</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ count($backups) }}</div>
                <div class="mt-1 text-xs text-gray-500">Auto-compressed (.gz)</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Media Storage</div>
                <div class="mt-1 text-2xl font-bold {{ $mediaDisk === 's3' ? 'text-cyan-600 dark:text-cyan-400' : 'text-amber-600 dark:text-amber-400' }}">
                    {{ strtoupper($mediaDisk) }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    {{ $mediaDisk === 's3' ? 'External Cloud Storage' : 'Local Web Server' }}
                </div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daily Schedule</div>
                <div class="mt-1 text-xl font-bold text-purple-600 dark:text-purple-400">02:00 AM</div>
                <div class="mt-1 text-xs text-gray-500">Retaining 14 days</div>
            </div>
        </div>

        {{-- Backups Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Local Database Backups</h3>
                    <p class="text-xs text-gray-500">Stored safely in <code>storage/app/backups/</code></p>
                </div>
            </div>

            @if(count($backups) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3">File Name</th>
                                <th class="px-6 py-3">File Size</th>
                                <th class="px-6 py-3">Created Date</th>
                                <th class="px-6 py-3">Age</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white text-xs">
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-o-document-arrow-down class="w-4 h-4 text-emerald-500" />
                                            <span>{{ $backup['filename'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $backup['size_formatted'] }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $backup['created_at'] }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $backup['age_human'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button
                                            wire:click="downloadBackup('{{ $backup['filename'] }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/40 dark:text-emerald-300 transition"
                                        >
                                            <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />
                                            Download
                                        </button>

                                        <button
                                            wire:click="deleteBackup('{{ $backup['filename'] }}')"
                                            wire:confirm="Are you sure you want to delete this backup file?"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-900/40 dark:text-rose-300 transition"
                                        >
                                            <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <x-heroicon-o-circle-stack class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                    <p class="font-medium">No backup files found.</p>
                    <p class="text-xs mt-1">Click the "Create Backup Now" button above to generate your first backup.</p>
                </div>
            @endif
        </div>

        {{-- Guide & Instructions Box --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Database Restore Guide --}}
            <div class="p-6 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                    <x-heroicon-o-arrow-path class="w-5 h-5 text-emerald-600" />
                    <h4>Database Backup & Restore Guide</h4>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                    Database backups are compressed using <strong>Gzip (.sql.gz)</strong> to minimize disk space. You can run backups or restore via Artisan commands from the terminal:
                </p>
                <div class="p-3 bg-gray-900 text-gray-100 rounded-lg text-xs font-mono space-y-1">
                    <div class="text-gray-400"># 1. Create a fresh backup</div>
                    <div>php artisan db:backup</div>
                    <div class="text-gray-400 pt-2"># 2. Restore interactively (shows a list of backups)</div>
                    <div>php artisan db:restore</div>
                    <div class="text-gray-400 pt-2"># 3. Restore specific file directly</div>
                    <div>php artisan db:restore --file=backup_xxx.sql.gz --force</div>
                </div>
                <div class="text-[11px] text-gray-500 pt-1">
                    💡 <strong>Crontab Automation:</strong> Add <code>* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1</code> on your production server. The daily backup will run automatically every morning at 02:00 AM.
                </div>
            </div>

            {{-- Remote Cloud Storage Setup Guide --}}
            <div class="p-6 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                    <x-heroicon-o-cloud-arrow-up class="w-5 h-5 text-cyan-600" />
                    <h4>Remote Cloud Storage (S3 / Cloudflare R2)</h4>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                    Currently, files are kept on the local web server. When server disk space becomes limited, you can switch seamlessly to external cloud storage:
                </p>
                <div class="p-3 bg-gray-900 text-gray-100 rounded-lg text-xs font-mono space-y-1">
                    <div class="text-gray-400"># 1. Set credentials in .env:</div>
                    <div>FILESYSTEM_MEDIA_DISK=s3</div>
                    <div>AWS_ACCESS_KEY_ID=your_key</div>
                    <div>AWS_SECRET_ACCESS_KEY=your_secret</div>
                    <div>AWS_BUCKET=your_bucket_name</div>
                    <div>AWS_ENDPOINT=https://your-r2-id.r2.cloudflarestorage.com</div>
                    <div>MEDIA_URL=https://pub-your-id.r2.dev</div>
                    <div class="text-gray-400 pt-2"># 2. Transfer existing media to Cloud:</div>
                    <div>php artisan media:sync-to-remote --disk=s3</div>
                </div>
                <div class="text-[11px] text-gray-500 pt-1">
                    💡 Cloudflare R2 has <strong>zero egress (bandwidth) fees</strong> and 10GB free storage, making it the ideal choice for storing photos and videos outside the web server.
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>
