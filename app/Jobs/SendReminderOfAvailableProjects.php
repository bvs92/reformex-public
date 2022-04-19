<?php

namespace App\Jobs;

use App\Demand;
use App\Mail\SendReminderOfAvailableProjectsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendReminderOfAvailableProjects implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $professionals = \App\Professional::all();

        $last_24_hours_demands = Demand::where('created_at', '>',
            Carbon::now()->subHours(24)->toDateTimeString()
        )->where('state', 1)->get();

        if ($last_24_hours_demands->count() < 1) {
            return;
        }

        if (!$professionals) {
            return;
        }

        foreach ($professionals as $pro) {
            if (!$pro->user->isActive()) {
                continue;
            }

            if ($pro->user->global_notification_settings) {
                if ($pro->user->global_notification_settings->daily_reminder) {
                    if ($pro->categories) { // check if pro has categories attached

                        if ($pro->categories->count() > 0) {

                            $pro_categories = $pro->categories;

                            $unlocked_demands = [];

                            foreach ($last_24_hours_demands as $demand) {

                                foreach ($demand->categories as $category) {
                                    // verifica daca profesionistul contine vreo categorie din demand
                                    if ($pro->categories->contains($category)) {
                                        if (!$demand->hasBuyer($pro->user)) {
                                            array_push($unlocked_demands, $demand);
                                        }
                                        break;
                                    }
                                }

                            }

                            if (count($unlocked_demands) > 0) {
                                Mail::to($pro->user)
                                    ->queue(new SendReminderOfAvailableProjectsMail($pro->user, count($unlocked_demands)));
                            }

                        }
                    }
                } // end if check if option active

            } // end if check globa_not

        }
    }
}
