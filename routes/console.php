<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Database Backups
|--------------------------------------------------------------------------
|
| Automatically creates a compressed backup of the database every day
| at 02:00 AM and cleans up backups older than 14 days.
|
*/
Schedule::command('db:backup --keep=14')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();
