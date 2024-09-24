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
        Commands\ReviewNotification::class,
        Commands\StoreCurrencyConversion::class, 
        Commands\SendBookingReminder::class,
        Commands\ReleaseAllocatedSlots::class,
        Commands\CancelUnpaidedBooking::class,
        Commands\GeneralCron::class,
        '\App\Console\Commands\SendReminderEmail'
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('review-notification:schedule')->daily();
        $schedule->command('store_currency_conversion:schedule')->twiceDaily();
        $schedule->command('send_booking_reminder:schedule')->daily();
        $schedule->command('release_allocated_slots:schedule')->daily();
        $schedule->command('cancel_unpaided_booking:schedule')->everyTenMinutes();
        $schedule->command('general_cron:schedule')->daily();
        
        if(config('app.env') == "prod"){
            $schedule->command('SendReminderEmail:checkintomorrow')->daily();
        }else{
            $filePath = '/opt/lampp/htdocs/test_cron.txt';
            $schedule->command('SendReminderEmail:checkintomorrow')->daily()->appendOutputTo($filePath);
        }
    }
}
