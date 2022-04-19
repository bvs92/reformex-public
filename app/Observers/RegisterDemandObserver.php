<?php

namespace App\Observers;

use App\Demand;
use App\Mail\SendReminderOfNewDemandMail;
use Illuminate\Support\Facades\Mail;

class RegisterDemandObserver
{
    /**
     * Handle the demand "created" event.
     *
     * @param  \App\Demand  $demand
     * @return void
     */
    public function created(Demand $demand)
    {

        $professionals = \App\Professional::all();

        foreach ($professionals as $pro) {
            if ($pro->user->isActive()) {

                if ($pro->user->global_notification_settings) {
                    if (boolval($pro->user->global_notification_settings->each_demand) == true) {
                        if ($pro->categories) { // check if pro has categories attached

                            if ($pro->categories->count() > 0) {

                                foreach ($demand->categories as $category) {
                                    // verifica daca profesionistul contine vreo categorie din demand
                                    if ($pro->categories->contains($category)) {
                                        Mail::to($pro->user)
                                            ->queue(new SendReminderOfNewDemandMail($pro->user, $demand));
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

    /**
     * Handle the demand "updated" event.
     *
     * @param  \App\Demand  $demand
     * @return void
     */
    public function updated(Demand $demand)
    {
        //
    }

    /**
     * Handle the demand "deleted" event.
     *
     * @param  \App\Demand  $demand
     * @return void
     */
    public function deleted(Demand $demand)
    {
        //
    }

    /**
     * Handle the demand "restored" event.
     *
     * @param  \App\Demand  $demand
     * @return void
     */
    public function restored(Demand $demand)
    {
        //
    }

    /**
     * Handle the demand "force deleted" event.
     *
     * @param  \App\Demand  $demand
     * @return void
     */
    public function forceDeleted(Demand $demand)
    {
        //
    }
}
