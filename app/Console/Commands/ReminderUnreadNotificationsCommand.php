<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderUnreadNotificationsMail;

class ReminderUnreadNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:unreadnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send to users on email a reminder of unread notifications.';

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
        $users = \App\User::all();

        $notifications = [];
        foreach($users as $user){
            $notifications[$user->id] = $user->unreadNotifications();
        }

        foreach($notifications as $key => $item){
            $user = $users->find($key);
            // echo $user->getName() . " are " . $item->count() . " notificari necitite.\n\t";
            if($item->count() > 0){
                // \Log::info($user->getName() . " are " . $item->count() . " notificari necitite.\n");
                // send email
                Mail::to($user)->send(new ReminderUnreadNotificationsMail($item->count(), $user));
            }
        }
        
    }
}
