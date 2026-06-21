<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Send daily reminders to users who haven't logged any transaction today
// Runs at 20:00 (8 PM) every day
Schedule::command('app:send-daily-reminders')->dailyAt('20:00');
