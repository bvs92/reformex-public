<?php

namespace App\Jobs;

use App\Banner;
use App\Notifications\BannerExpiredNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class CheckBannerStatus implements ShouldQueue
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
        $banners = Banner::where('status', 1)->get();

        if ($banners->count() > 0) {
            foreach ($banners as $banner) {
                $banner_ends = Carbon::parse($banner->ends_at);
                if ($banner_ends->lt(now())) {
                    // return $banner;
                    $banner->status = 0;
                    $banner->editable = 1;
                    $banner->paid = 0;
                    $banner->processing = 0;
                    $banner->rejected = 0;
                    $banner->save();

                    // notify owner
                    if ($banner->user) {
                        Notification::send($banner->user, new BannerExpiredNotification($banner));
                    }
                }
            }
        } // end if

    }
}
