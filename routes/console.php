<?php

use Illuminate\Support\Facades\Schedule;

// Single cron on the host: * * * * * php artisan schedule:run
// Everything below runs off that one cron entry.

Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
Schedule::command('photos:cleanup')->daily();
Schedule::command('rates:fetch')->dailyAt('09:00');
Schedule::command('disk:check')->daily();
