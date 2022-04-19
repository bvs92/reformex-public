<?php

namespace App\Console\Commands;

use App\Jobs\LogTest;
use Illuminate\Console\Command;

class LogTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:logtest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        echo "log test command";
        LogTest::dispatch()->delay(now()->addMinutes(1));
    }
}
