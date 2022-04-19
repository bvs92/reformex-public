<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendReminderOfAvailableProjects;

class SendReminderOfAvailableProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:availableprojects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder of available projects for each user that is a PRO.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo 'sending reminder to users';
        // SendReminderOfAvailableProjects::dispatch()->delay(now()->addMinutes(1));
        SendReminderOfAvailableProjects::dispatch()->delay(now()->addMinutes(1));
    }
}
