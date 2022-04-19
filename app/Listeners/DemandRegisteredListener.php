<?php

namespace App\Listeners;

use App\Events\DemandRegistered;
use App\Mail\SendReminderOfNewDemandMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class DemandRegisteredListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DemandRegistered  $event
     * @return void
     */
    public function handle(DemandRegistered $event)
    {
        $professionals = \App\Professional::all();

        foreach ($professionals as $pro) {
            if ($pro->user->isActive()) {

                if ($pro->user->global_notification_settings) {
                    if (boolval($pro->user->global_notification_settings->each_demand) == true) {
                        if ($pro->categories) { // check if pro has categories attached

                            if ($pro->categories->count() > 0) {

                                foreach ($event->demand->categories as $category) {
                                    // verifica daca profesionistul contine vreo categorie din demand
                                    if ($pro->categories->contains($category)) {
                                        Mail::to($pro->user)
                                            ->queue(new SendReminderOfNewDemandMail($pro->user, $event->demand));
                                        break;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
    }
}
