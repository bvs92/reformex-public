<?php

namespace App\Console;

use App\Jobs\CheckBannerStatus;
use App\Jobs\SendReminderOfAvailableProjects;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('inspire')->hourly();
        // check banner status
        $schedule->job(new CheckBannerStatus)->withoutOverlapping()->timezone('Europe/Bucharest')->everyMinute()->runInBackground(); // every minute

        // check new demands
        $schedule->job(new SendReminderOfAvailableProjects)->withoutOverlapping()->timezone('Europe/Bucharest')->dailyAt('12:02')->runInBackground(); // 2 times per day: dimineata | dupa-amiaza

        // delete failed jobs
        $schedule->command('queue:flush')->dailyAt('00:00'); // delete failed jobs
        // $schedule->job(new SendReminderOfAvailableProjects)->withoutOverlapping()->timezone('Europe/Bucharest')->twiceDaily(9, 15)->runInBackground(); // 2 times per day: dimineata | dupa-amiaza
        // $schedule->command('reminder:unreadnotifications')->withoutOverlapping()->timezone('Europe/Bucharest')->dailyAt('12:02')->runInBackground(); // 1 data pe zi: dupa-amiaza
        // $schedule->command('delete:invaliddemands')->withoutOverlapping()->timezone('Europe/Bucharest')->dailyAt('01:02')->runInBackground(); // 1 data pe zi: dimineata la 1
        // $sched-ule->command('command:logtest')->everyMinute();

        // $schedule->call(function(){
        //     Log::info('Numar: ...');
        // })->everyMinute()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
