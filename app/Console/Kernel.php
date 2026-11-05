<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CheckGuaranteesExpiry::class,
        \App\Console\Commands\FixProjectLocations::class,
        \App\Console\Commands\BackfillOpportunityKdv::class,
        \App\Console\Commands\ImportVouchersFromJson::class,
        \App\Console\Commands\PopulatePartnersSubcontractor::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Run guarantee expiry check daily
        $schedule->command('guarantees:check-expiry')->daily();

        // Send payment reminders at 8 AM every day
        $schedule->job(\App\Jobs\SendPaymentReminders::class)->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
